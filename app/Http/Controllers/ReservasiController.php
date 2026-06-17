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
        return view('reservasi.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('reservasi.index');
    }

    public function show($id)
    {
        $reservasi = DB::table('reservasi')
            ->join('tamu', 'reservasi.id_tamu', '=', 'tamu.id_tamu')
            ->select(
                'reservasi.*',
                'tamu.nama_lengkap',
                'tamu.no_identitas',
                'tamu.kontak'
            )
            ->where('reservasi.id_reservasi', $id)
            ->first();

        return view('reservasi.show', compact('reservasi'));
    }

    public function edit($id)
    {
        return redirect()->route('reservasi.index');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('reservasi.index');
    }

    public function destroy($id)
    {
        return redirect()->route('reservasi.index');
    }

    // ──── PBI-28: Pencarian Kamar ──────────────────────────────────────────
    public function cariKamar()
    {
        $tipeKamar = TipeKamar::all();
        return view('reservasi.cari-kamar', compact('tipeKamar'));
    }

    public function prosesCariKamar(Request $request)
    {
        $request->validate([
            'tanggal_checkin' => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'tipe_kamar_id' => 'required|exists:tipe_kamar,id_tipe',
        ]);

        $kamarTersedia = Kamar::where('tipe_kamar_id', $request->tipe_kamar_id)
            ->where('status_kamar', 'tersedia')
            ->get();

        return view('reservasi.hasil-kamar', compact('kamarTersedia', 'request'));
    }

    // ──── PBI-29: Pembuatan Reservasi ──────────────────────────────────────
    public function buatReservasi(Request $request)
    {
        $kamar = Kamar::findOrFail($request->kamar_id);
        return view('reservasi.buat-reservasi', compact('kamar', 'request'));
    }

    public function simpanReservasi(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamar,id_kamar',
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'nama_tamu' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:50',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:100',
        ]);

        $tamu = Tamu::create([
            'nama' => $request->nama_tamu,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
        ]);

        $reservasi = Reservasi::create([
            'id_tamu' => $tamu->id_tamu,
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'status_reservasi' => 'reserved',
        ]);

        DetailKamar::create([
            'id_reservasi' => $reservasi->id_reservasi,
            'id_kamar' => $request->kamar_id,
        ]);

        return redirect()->route('reservasi.pembayaran-dp', $reservasi->id_reservasi)
            ->with('success', 'Reservasi berhasil dibuat! Silakan bayar DP.');
    }

    // ──── PBI-30: Pembayaran DP ─────────────────────────────────────────────
    public function formPembayaranDP($id_reservasi)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar.tipeKamar'])->findOrFail($id_reservasi);

        $jumlahMalam = date_diff(date_create($reservasi->tanggal_checkin), date_create($reservasi->tanggal_checkout))->days;
        $totalHarga = $reservasi->detailKamar->first()->kamar->tipeKamar->harga_per_malam * $jumlahMalam;

        return view('reservasi.pembayaran-dp', compact('reservasi', 'totalHarga'));
    }

    public function simpanPembayaranDP(Request $request, $id_reservasi)
    {
        $request->validate([
            'jumlah_dp' => 'required|numeric|min:1',
        ]);

        $reservasi = Reservasi::findOrFail($id_reservasi);

        Pembayaran::create([
            'id_reservasi' => $reservasi->id_reservasi,
            'jumlah' => $request->jumlah_dp,
            'tipe_pembayaran' => 'dp',
            'status_pembayaran' => 'lunas',
            'tanggal_pembayaran' => now(),
        ]);

        return redirect()->route('reservasi.cari-kamar')
            ->with('success', 'Pembayaran DP berhasil! Reservasi sudah terkonfirmasi.');
    }
}