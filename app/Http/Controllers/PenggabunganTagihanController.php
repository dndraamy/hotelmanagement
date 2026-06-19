<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Tagihan;
use App\Models\PesananRestoran;
use Carbon\Carbon;
use DB;

class PenggabunganTagihanController extends Controller
{
    /**
     * Display the bill merge interface.
     */
    public function index(Request $request)
    {
        // Get all active reservations with their guests and rooms
        $reservations = Reservasi::with(['tamu', 'detailKamar.kamar.tipeKamar'])
            ->whereIn('status_reservasi', ['Checked-In', 'Checked-Out'])
            ->orderBy('tgl_checkin', 'desc')
            ->get();

        $selectedReservasi = null;
        $nights = 0;
        $calculatedRoomTotal = 0;
        $tagihan = null;
        $mergedOrders = collect();
        $pendingOrders = collect();
        $searchedOrder = null;

        if ($request->filled('id_reservasi')) {
            $selectedReservasi = Reservasi::with([
                'tamu', 
                'detailKamar.kamar.tipeKamar', 
                'tagihanTambahan.masterBiayaTambahan'
            ])->find($request->id_reservasi);

            if ($selectedReservasi) {
                // Calculate stay duration
                $checkin = Carbon::parse($selectedReservasi->tgl_checkin);
                $checkout = Carbon::parse($selectedReservasi->tgl_checkout);
                $nights = max(1, $checkout->diffInDays($checkin));

                // Calculate room total
                foreach ($selectedReservasi->detailKamar as $detail) {
                    if ($detail->kamar && $detail->kamar->tipeKamar) {
                        $calculatedRoomTotal += $detail->kamar->tipeKamar->harga_per_malam * $nights;
                    }
                }

                // Ensure Tagihan entry exists and sync values
                $totalTambahan = $selectedReservasi->tagihanTambahan->sum('nominal_akhir');
                
                $tagihan = Tagihan::firstOrCreate(
                    ['id_reservasi' => $selectedReservasi->id_reservasi],
                    [
                        'total_kamar' => $calculatedRoomTotal,
                        'total_restoran' => 0,
                        'total_tambahan' => $totalTambahan,
                        'grand_total' => $calculatedRoomTotal + $totalTambahan,
                        'status_tagihan' => 'Belum Lunas'
                    ]
                );

                // Sync details if updated
                $mergedRestoranTotal = PesananRestoran::where('id_reservasi', $selectedReservasi->id_reservasi)
                    ->where('status_pembayaran', 'Charge to Room')
                    ->sum('total_harga');

                if ($tagihan->total_kamar != $calculatedRoomTotal || 
                    $tagihan->total_tambahan != $totalTambahan || 
                    $tagihan->total_restoran != $mergedRestoranTotal) {
                    
                    $tagihan->total_kamar = $calculatedRoomTotal;
                    $tagihan->total_tambahan = $totalTambahan;
                    $tagihan->total_restoran = $mergedRestoranTotal;
                    $tagihan->grand_total = $calculatedRoomTotal + $mergedRestoranTotal + $totalTambahan;
                    $tagihan->save();
                }

                // Get restaurant orders
                // 1. Merged to room
                $mergedOrders = PesananRestoran::with('detailPesananRestoran.itemMenu')
                    ->where('id_reservasi', $selectedReservasi->id_reservasi)
                    ->where('status_pembayaran', 'Charge to Room')
                    ->get();

                // 2. Linked to reservation but not yet merged
                $pendingOrders = PesananRestoran::with('detailPesananRestoran.itemMenu')
                    ->where('id_reservasi', $selectedReservasi->id_reservasi)
                    ->where('status_pembayaran', 'Belum Lunas')
                    ->get();

                // Handle external POS order lookup
                if ($request->filled('search_order_id')) {
                    $searchedOrder = PesananRestoran::with('detailPesananRestoran.itemMenu')
                        ->where('id_pesanan', $request->search_order_id)
                        ->where(function($q) use ($selectedReservasi) {
                            $q->whereNull('id_reservasi')
                              ->orWhere('id_reservasi', '!=', $selectedReservasi->id_reservasi);
                        })
                        ->where('status_pembayaran', 'Belum Lunas')
                        ->first();
                }
            }
        }

    return view('kas.penggabungan-tagihan.index', compact(
    'reservations',
    'selectedReservasi',
    'nights',
    'calculatedRoomTotal',
    'tagihan',
    'mergedOrders',
    'pendingOrders',
    'searchedOrder'
));
    }

    /**
     * Merge selected restaurant orders to the room bill.
     */
    public function merge(Request $request)
    {
        $request->validate([
            'id_reservasi' => 'required|exists:reservasi,id_reservasi',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:pesanan_restoran,id_pesanan',
        ]);

        $id_reservasi = $request->id_reservasi;
        $orderIds = $request->order_ids;

        DB::transaction(function() use ($id_reservasi, $orderIds) {
            // Retrieve selected orders and verify they are eligible for merging
            $orders = PesananRestoran::whereIn('id_pesanan', $orderIds)
                ->where(function($query) use ($id_reservasi) {
                    $query->where('id_reservasi', $id_reservasi)
                          ->orWhereNull('id_reservasi')
                          ->orWhere('id_reservasi', '!=', $id_reservasi);
                })
                ->where('status_pembayaran', 'Belum Lunas')
                ->get();

            foreach ($orders as $order) {
                $order->id_reservasi = $id_reservasi;
                $order->status_pembayaran = 'Charge to Room';
                $order->save();
            }

            $this->recalculateTagihan($id_reservasi);
        });

        return redirect()->route('penggabungan-tagihan.index', ['id_reservasi' => $id_reservasi])
            ->with('success', count($orderIds) . ' pesanan restoran berhasil digabungkan ke tagihan kamar.');
    }

    /**
     * Unmerge a restaurant order from the room bill.
     */
    public function unmerge(Request $request)
    {
        $request->validate([
            'id_reservasi' => 'required|exists:reservasi,id_reservasi',
            'id_pesanan' => 'required|exists:pesanan_restoran,id_pesanan',
        ]);

        $id_reservasi = $request->id_reservasi;
        $id_pesanan = $request->id_pesanan;

        DB::transaction(function() use ($id_reservasi, $id_pesanan) {
            $order = PesananRestoran::where('id_pesanan', $id_pesanan)
                ->where('id_reservasi', $id_reservasi)
                ->where('status_pembayaran', 'Charge to Room')
                ->firstOrFail();

            $order->status_pembayaran = 'Belum Lunas';
            // Note: we keep the id_reservasi link for reference, but set status back to Belum Lunas
            $order->save();

            $this->recalculateTagihan($id_reservasi);
        });

        return redirect()->route('penggabungan-tagihan.index', ['id_reservasi' => $id_reservasi])
            ->with('success', 'Pesanan restoran berhasil dilepas dari tagihan kamar.');
    }

    /**
     * Recalculate bill summary details.
     */
    private function recalculateTagihan($id_reservasi)
    {
        $reservasi = Reservasi::with(['detailKamar.kamar.tipeKamar', 'tagihanTambahan', 'tagihan'])->findOrFail($id_reservasi);

        // Stay nights calculation
        $checkin = Carbon::parse($reservasi->tgl_checkin);
        $checkout = Carbon::parse($reservasi->tgl_checkout);
        $nights = max(1, $checkout->diffInDays($checkin));

        // Calculate room total
        $totalKamar = 0;
        foreach ($reservasi->detailKamar as $detail) {
            if ($detail->kamar && $detail->kamar->tipeKamar) {
                $totalKamar += $detail->kamar->tipeKamar->harga_per_malam * $nights;
            }
        }

        // Sum of merged restaurant orders
        $totalRestoran = PesananRestoran::where('id_reservasi', $id_reservasi)
            ->where('status_pembayaran', 'Charge to Room')
            ->sum('total_harga');

        // Sum of additional charges
        $totalTambahan = $reservasi->tagihanTambahan->sum('nominal_akhir');

        // Update tagihan details
        $tagihan = Tagihan::firstOrCreate(
            ['id_reservasi' => $id_reservasi],
            [
                'total_kamar' => $totalKamar,
                'total_restoran' => $totalRestoran,
                'total_tambahan' => $totalTambahan,
                'grand_total' => $totalKamar + $totalRestoran + $totalTambahan,
                'status_tagihan' => 'Belum Lunas'
            ]
        );

        $tagihan->total_kamar = $totalKamar;
        $tagihan->total_restoran = $totalRestoran;
        $tagihan->total_tambahan = $totalTambahan;
        $tagihan->grand_total = $totalKamar + $totalRestoran + $totalTambahan;
        $tagihan->save();
    }
}
