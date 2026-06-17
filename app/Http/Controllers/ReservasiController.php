<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Models\Reservasi;
use App\Models\DetailKamar;
use App\Models\Pembayaran;

class ReservasiController extends Controller
{
    // ──── PBI-31: Dashboard & Daftar Reservasi ────────────────────────────
    public function index()
    {
        $reservasi = DB::table('reservasi')
            ->join('tamu', 'reservasi.id_tamu', '=', 'tamu.id_tamu')
            ->select(
                'reservasi.id_reservasi',
                'tamu.nama_lengkap',
                'reservasi.tgl_checkin',
                'reservasi.tgl_checkout',
                'reservasi.status_reservasi'
            )
            ->orderBy('reservasi.created_at', 'desc')
            ->get();

        $totalReservasi     = DB::table('reservasi')->count();
        $confirmedReservasi = DB::table('reservasi')->where('status_reservasi', 'Confirmed')->count();
        $pendingReservasi   = DB::table('reservasi')->where('status_reservasi', 'Menunggu DP')->count();
        $cancelledReservasi = DB::table('reservasi')->where('status_reservasi', 'Cancelled')->count();

        return view('reservasi.index', compact(
            'reservasi',
            'totalReservasi',
            'confirmedReservasi',
            'pendingReservasi',
            'cancelledReservasi'
        ));
    }

    public function create()
    {
        return redirect()->route('reservasi.cari-kamar');
    }

    public function show($id)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar.tipeKamar', 'pembayaran'])
            ->findOrFail($id);

        return view('reservasi.show', compact('reservasi'));
    }

    /**
     * Show the form for editing the specified reservation.
     */
    public function edit($id)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar.tipeKamar'])->findOrFail($id);
        $tamu = Tamu::all();
        $tipeKamar = TipeKamar::all();

        // Ambil semua kamar yang tersedia atau kamar yang sedang dipakai oleh reservasi ini
        $kamar = Kamar::with('tipeKamar')
            ->where(function ($query) use ($reservasi) {
                $query->where('status_kamar', 'Kosong')
                    ->orWhere('id_kamar', $reservasi->detailKamar->first()->id_kamar ?? 0);
            })
            ->get();

        return view('reservasi.edit', compact('reservasi', 'tamu', 'kamar', 'tipeKamar'));
    }

    /**
     * Update the specified reservation in storage.
     */
    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);

        $validated = $request->validate([
            'id_tamu' => 'required|exists:tamu,id_tamu',
            'id_kamar' => 'required|exists:kamar,id_kamar',
            'tgl_checkin' => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
            'status_reservasi' => 'required|in:Menunggu DP,Confirmed,Checked-In,Checked-Out,Cancelled',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Hitung ulang biaya kamar berdasarkan tanggal baru
        $kamar = Kamar::with('tipeKamar')->findOrFail($request->id_kamar);
        $jumlahMalam = (int) date_diff(
            date_create($request->tgl_checkin),
            date_create($request->tgl_checkout)
        )->days;

        $biayaKamar = $kamar->tipeKamar->harga_per_malam * $jumlahMalam;

        // Update data reservasi
        $reservasi->update([
            'id_tamu' => $request->id_tamu,
            'tgl_checkin' => $request->tgl_checkin,
            'tgl_checkout' => $request->tgl_checkout,
            'status_reservasi' => $request->status_reservasi,
            'biaya_kamar' => $biayaKamar,
            'total_tagihan' => $biayaKamar,
        ]);

        // Update detail kamar jika berubah
        $detailKamar = DetailKamar::where('id_reservasi', $id)->first();
        if ($detailKamar) {
            // Kembalikan status kamar lama ke Kosong
            $kamarLama = Kamar::find($detailKamar->id_kamar);
            if ($kamarLama && $kamarLama->id_kamar != $request->id_kamar) {
                $kamarLama->update(['status_kamar' => 'Kosong']);
            }

            // Update detail kamar dengan kamar baru
            $detailKamar->update(['id_kamar' => $request->id_kamar]);

            // Update status kamar baru
            $kamarBaru = Kamar::find($request->id_kamar);
            if ($kamarBaru && $reservasi->status_reservasi != 'Cancelled' && $reservasi->status_reservasi != 'Checked-Out') {
                $kamarBaru->update(['status_kamar' => 'Terisi']);
            }
        }

        return redirect()->route('reservasi.index')
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);

        // Kembalikan status kamar ke Kosong
        $detailKamar = DetailKamar::where('id_reservasi', $id)->first();
        if ($detailKamar) {
            $kamar = Kamar::find($detailKamar->id_kamar);
            if ($kamar) {
                $kamar->update(['status_kamar' => 'Kosong']);
            }
        }

        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('success', 'Reservasi berhasil dihapus.');
    }

    // ──── PBI-28: Pencarian Kamar ──────────────────────────────────────────
    public function cariKamar(Request $request)
    {
        $tipeKamar = TipeKamar::all();
        $kamarTersedia = collect();
        $sudahCari = false;

        if ($request->has('tgl_checkin')) {
            $sudahCari = true;

            $request->validate([
                'tgl_checkin'  => 'required|date|after_or_equal:today',
                'tgl_checkout' => 'required|date|after:tgl_checkin',
                'id_tipe'      => 'nullable|exists:tipe_kamar,id_tipe',
            ]);

            // Cari kamar dengan status 'Kosong' DAN belum punya reservasi
            $query = Kamar::with('tipeKamar')
                ->where('status_kamar', 'Kosong')
                ->whereDoesntHave('detailKamar.reservasi', function ($q) use ($request) {
                    $q->whereIn('status_reservasi', ['Menunggu DP', 'Confirmed', 'Checked-In'])
                        ->where(function ($q2) use ($request) {
                            $q2->where('tgl_checkin', '<', $request->tgl_checkout)
                                ->where('tgl_checkout', '>', $request->tgl_checkin);
                        });
                });

            if ($request->filled('id_tipe')) {
                $query->where('id_tipe', $request->id_tipe);
            }

            $kamarTersedia = $query->get();
        }

        return view('reservasi.cari-kamar', compact('tipeKamar', 'kamarTersedia', 'sudahCari'));
    }

    // ──── PBI-29: Pembuatan Reservasi ──────────────────────────────────────
    public function buatReservasi(Request $request)
    {
        // Jika tidak ada parameter, redirect ke halaman cari kamar
        if (!$request->has('id_kamar') || !$request->has('tgl_checkin') || !$request->has('tgl_checkout')) {
            return redirect()->route('reservasi.cari-kamar');
        }

        $request->validate([
            'id_kamar'     => 'required|exists:kamar,id_kamar',
            'tgl_checkin'  => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
        ]);

        $kamar = Kamar::with('tipeKamar')->findOrFail($request->id_kamar);
        $jumlahMalam = (int) date_diff(
            date_create($request->tgl_checkin),
            date_create($request->tgl_checkout)
        )->days;
        $totalHarga = $kamar->tipeKamar->harga_per_malam * $jumlahMalam;

        return view('reservasi.buat-reservasi', compact(
            'kamar',
            'jumlahMalam',
            'totalHarga'
        ))->with([
            'tgl_checkin'  => $request->tgl_checkin,
            'tgl_checkout' => $request->tgl_checkout,
        ]);
    }

    public function simpanReservasi(Request $request)
    {
        $request->validate([
            'id_kamar'       => 'required|exists:kamar,id_kamar',
            'tgl_checkin'    => 'required|date',
            'tgl_checkout'   => 'required|date|after:tgl_checkin',
            'nama_lengkap'   => 'required|string|max:255',
            'tipe_identitas' => 'required|string|max:50',
            'no_identitas'   => 'required|string|max:50',
            'kontak'         => 'required|string|max:50',
        ]);

        // Cek apakah kamar masih tersedia
        $kamar = Kamar::with('tipeKamar')->findOrFail($request->id_kamar);

        // Cek overlap reservasi
        $overlap = Reservasi::whereHas('detailKamar', function ($q) use ($request) {
            $q->where('id_kamar', $request->id_kamar);
        })->whereIn('status_reservasi', ['Menunggu DP', 'Confirmed', 'Checked-In'])
            ->where(function ($q) use ($request) {
                $q->where('tgl_checkin', '<', $request->tgl_checkout)
                    ->where('tgl_checkout', '>', $request->tgl_checkin);
            })->exists();

        if ($overlap) {
            return back()->with('error', 'Kamar sudah dipesan untuk tanggal tersebut!');
        }

        // Buat tamu
        $tamu = Tamu::create([
            'nama_lengkap'   => $request->nama_lengkap,
            'tipe_identitas' => $request->tipe_identitas,
            'no_identitas'   => $request->no_identitas,
            'kontak'         => $request->kontak,
        ]);

        $jumlahMalam = (int) date_diff(
            date_create($request->tgl_checkin),
            date_create($request->tgl_checkout)
        )->days;
        $biayaKamar = $kamar->tipeKamar->harga_per_malam * $jumlahMalam;

        // Buat reservasi
        $reservasi = Reservasi::create([
            'id_tamu'           => $tamu->id_tamu,
            'tanggal_reservasi' => now(),
            'tgl_checkin'       => $request->tgl_checkin,
            'tgl_checkout'      => $request->tgl_checkout,
            'status_reservasi'  => 'Menunggu DP',
            'biaya_kamar'       => $biayaKamar,
            'total_tagihan'     => $biayaKamar,
        ]);

        // Detail kamar
        DetailKamar::create([
            'id_reservasi' => $reservasi->id_reservasi,
            'id_kamar'     => $kamar->id_kamar,
        ]);

        // Update status kamar jadi 'Terisi'
        $kamar->update(['status_kamar' => 'Terisi']);

        return redirect()->route('reservasi.pembayaran-dp', $reservasi->id_reservasi)
            ->with('success', 'Reservasi berhasil dibuat! Silakan catat pembayaran DP.');
    }

    // ──── PBI-30: Pembayaran DP ─────────────────────────────────────────────
    public function formPembayaranDP($id_reservasi)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar.tipeKamar', 'pembayaran'])
            ->findOrFail($id_reservasi);

        // Cek apakah DP sudah tercatat
        $dpSudahDibayar = $reservasi->pembayaran()->where('jenis_pembayaran', 'DP')->exists();

        // Hitung total DP yang sudah diterima
        $totalDPDiterima = $reservasi->pembayaran()
            ->where('jenis_pembayaran', 'DP')
            ->sum('nominal');

        // Hitung sisa tagihan
        $sisaTagihan = $reservasi->total_tagihan - $totalDPDiterima;

        return view('reservasi.dp', compact(
            'reservasi',
            'dpSudahDibayar',
            'totalDPDiterima',
            'sisaTagihan'
        ));
    }

    public function simpanPembayaranDP(Request $request, $id_reservasi)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string|max:50',
            'tanggal_terima' => 'required|date',
            'nomor_referensi' => 'nullable|string|max:100',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $reservasi = Reservasi::findOrFail($id_reservasi);

        // Upload bukti pembayaran
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $buktiPath = $file->storeAs('bukti_dp', $filename, 'public');
        }

        Pembayaran::create([
            'id_tagihan' => null,
            'id_reservasi' => $reservasi->id_reservasi,
            'jenis_pembayaran' => 'DP',
            'metode_bayar' => $request->metode_bayar,
            'nominal' => $request->nominal,
            'tanggal_bayar' => $request->tanggal_terima,
            'nomor_referensi' => $request->nomor_referensi,
            'bukti_pembayaran' => $buktiPath,
            'status' => 'Lunas',
        ]);

        // Update status reservasi menjadi Confirmed jika DP sudah cukup
        $totalDPDiterima = Pembayaran::where('id_reservasi', $reservasi->id_reservasi)
            ->where('jenis_pembayaran', 'DP')
            ->sum('nominal');

        if ($totalDPDiterima >= ($reservasi->total_tagihan * 0.5)) {
            $reservasi->update(['status_reservasi' => 'Confirmed']);
        }

        return redirect()->route('reservasi.pembayaran-dp', $reservasi->id_reservasi)
            ->with('success', 'Pembayaran DP berhasil dicatat!');
    }

    public function cekStatusDP($id_reservasi)
    {
        $reservasi = Reservasi::with('pembayaran')->findOrFail($id_reservasi);
        $dpSudahDibayar = $reservasi->pembayaran()->where('jenis_pembayaran', 'DP')->exists();

        return response()->json([
            'dp_sudah_dibayar' => $dpSudahDibayar,
            'total_dp' => $reservasi->pembayaran()->where('jenis_pembayaran', 'DP')->sum('nominal'),
            'total_tagihan' => $reservasi->total_tagihan,
        ]);
    }
}
