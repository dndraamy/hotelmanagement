<?php

namespace App\Http\Controllers;

use App\Models\ItemMenu;
use App\Models\PesananRestoran;
use App\Models\DetailPesananRestoran;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosRestoranController extends Controller
{
    /**
     * Tampilkan halaman utama POS Restoran.
     * Memuat daftar item menu dan (opsional) pesanan yang dipilih untuk diproses.
     */
    public function index(Request $request)
    {
        // Ambil semua item menu untuk ditampilkan di panel kiri
        $menuItems = ItemMenu::all()->groupBy('kategori');

        // Ambil pesanan yang statusnya 'Selesai' dan belum dibayar (Pending Payment)
        // agar bisa diproses melalui Charge to Room
        $pesananPending = PesananRestoran::with([
                'detailPesananRestoran.itemMenu',
                'reservasi.tamu',
                'reservasi.detailKamar.kamar',
            ])
            ->whereIn('status_pembayaran', ['Belum Dibayar', 'Pending'])
            ->latest()
            ->get();

        // Ambil satu pesanan spesifik jika ada ?pesanan_id= di query string
        $selectedPesanan = null;
        if ($request->has('pesanan_id')) {
            $selectedPesanan = PesananRestoran::with([
                'detailPesananRestoran.itemMenu',
                'reservasi.tamu',
                'reservasi.detailKamar.kamar',
            ])->find($request->pesanan_id);
        }

        return view('pos-restoran.index', compact('menuItems', 'pesananPending', 'selectedPesanan'));
    }

    /**
     * API Endpoint: Mengembalikan daftar tamu yang sedang Checked-In
     * dalam format JSON untuk kebutuhan dropdown dinamis di frontend.
     */
    public function getCheckedInGuests()
    {
        $reservasiCheckedIn = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Checked-In')
            ->get()
            ->map(function ($reservasi) {
                // Kumpulkan semua nomor kamar yang terhubung ke reservasi ini
                $nomorKamar = $reservasi->detailKamar
                    ->map(fn($dk) => $dk->kamar?->nomor_kamar)
                    ->filter()
                    ->implode(', ');

                return [
                    'id_reservasi'   => $reservasi->id_reservasi,
                    'nama_tamu'      => $reservasi->tamu?->nama_lengkap ?? 'Tamu Tidak Diketahui',
                    'nomor_kamar'    => $nomorKamar ?: 'No. Kamar N/A',
                    'tgl_checkout'   => $reservasi->tgl_checkout,
                    'label'          => ($reservasi->tamu?->nama_lengkap ?? 'N/A') . ' — Kamar ' . ($nomorKamar ?: 'N/A'),
                ];
            });

        return response()->json($reservasiCheckedIn);
    }

    /**
     * Memproses aksi "Charge to Room":
     * - Memvalidasi reservasi yang dipilih
     * - Memperbarui kolom id_reservasi dan status_pembayaran pada pesanan
     * - Mengembalikan flash message konfirmasi
     */
    public function chargeToRoom(Request $request, $id_pesanan)
    {
        $request->validate([
            'id_reservasi' => 'required|exists:reservasi,id_reservasi',
        ], [
            'id_reservasi.required' => 'Mohon pilih kamar tamu terlebih dahulu.',
            'id_reservasi.exists'   => 'Reservasi yang dipilih tidak ditemukan.',
        ]);

        // Pastikan reservasi memang sedang Checked-In
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('id_reservasi', $request->id_reservasi)
            ->where('status_reservasi', 'Checked-In')
            ->first();

        if (!$reservasi) {
            return redirect()->back()
                ->with('error', 'Reservasi yang dipilih tidak aktif atau tamu belum Check-In.')
                ->withInput();
        }

        // Pastikan pesanan ada dan belum diproses sebagai Charge to Room
        $pesanan = PesananRestoran::find($id_pesanan);

        if (!$pesanan) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak ditemukan.')
                ->withInput();
        }

        if ($pesanan->status_pembayaran === 'Charge to Room') {
            return redirect()->back()
                ->with('error', 'Pesanan ini sudah ditagihkan ke kamar sebelumnya.');
        }

        // Dapatkan nomor kamar untuk pesan konfirmasi
        $nomorKamar = $reservasi->detailKamar
            ->map(fn($dk) => $dk->kamar?->nomor_kamar)
            ->filter()
            ->implode(', ');

        $namaTamu = $reservasi->tamu?->nama_lengkap ?? 'Tamu';

        // Update pesanan: simpan id_reservasi dan ubah status pembayaran
        $pesanan->update([
            'id_reservasi'      => $request->id_reservasi,
            'status_pembayaran' => 'Charge to Room',
        ]);

        return redirect()->route('pos-restoran.index')
            ->with('success',
                "✓ Tagihan pesanan #{$pesanan->id_pesanan} berhasil diteruskan ke Kamar {$nomorKamar} atas nama {$namaTamu}."
            );
    }

    /**
     * Membuat pesanan baru (simpan ke DB) dari keranjang sesi.
     * Dipanggil saat Petugas menekan "Proses Pesanan" sebelum Charge to Room.
     */
    public function buatPesanan(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id_item' => 'required|exists:item_menu,id_item',
            'items.*.qty'     => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalHarga = 0;
            $details = [];

            foreach ($request->items as $item) {
                $menu = ItemMenu::findOrFail($item['id_item']);
                $subtotal = $menu->harga * $item['qty'];
                $totalHarga += $subtotal;

                $details[] = [
                    'id_item'  => $menu->id_item,
                    'qty'      => $item['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            // Buat header pesanan
            $pesanan = PesananRestoran::create([
                'id_reservasi'      => null,
                'tanggal_pesanan'   => now(),
                'total_harga'       => $totalHarga,
                'status_pembayaran' => 'Belum Dibayar',
                'status_pesanan'    => 'Pending',
            ]);

            // Simpan detail pesanan
            foreach ($details as $detail) {
                $detail['id_pesanan'] = $pesanan->id_pesanan;
                DetailPesananRestoran::create($detail);
            }

            DB::commit();

            return redirect()->route('pos-restoran.index', ['pesanan_id' => $pesanan->id_pesanan])
                ->with('info', "Pesanan #{$pesanan->id_pesanan} berhasil dibuat. Pilih metode pembayaran.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat pesanan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Cetak Struk Dapur (Lembar Instruksi Pembuatan Makanan).
     * Format PDF sederhana tanpa menampilkan harga.
     */
    public function cetakStrukDapur($id_pesanan)
    {
        $pesanan = PesananRestoran::with([
            'detailPesananRestoran.itemMenu',
            'reservasi.tamu',
            'reservasi.detailKamar.kamar',
        ])->findOrFail($id_pesanan);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pos-restoran.struk-dapur', compact('pesanan'));
        
        // Kalkulasi tinggi kertas dinamis menyesuaikan jumlah item
        // Tinggi dasar (header, info tamu, footer) sekitar ~280pt
        // Tinggi per item makanan sekitar ~35pt
        $tinggiDasar = 280; 
        $tinggiPerItem = 35;
        $totalTinggi = $tinggiDasar + ($pesanan->detailPesananRestoran->count() * $tinggiPerItem);

        // Atur ukuran kertas: Lebar 226pt (~80mm), Tinggi mengikuti jumlah item
        $pdf->setPaper(array(0, 0, 226, $totalTinggi), 'portrait'); 

        return $pdf->stream('Struk_Dapur_Pesanan_' . $pesanan->id_pesanan . '.pdf');
    }
}
