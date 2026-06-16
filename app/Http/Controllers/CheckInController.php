<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckInController extends Controller
{
    // ─────────────────────────────────────────────
    // INDEX: Dua tab — Confirmed & Checked-In
    // ─────────────────────────────────────────────
    public function index()
    {
        $reservasiPending = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Confirmed')
            ->orderBy('tgl_checkin', 'asc')
            ->get();

        $reservasiCheckedIn = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Checked-In')
            ->orderBy('tgl_checkout', 'asc')
            ->get();

        $totalConfirmed = $reservasiPending->count();
        $totalCheckedIn = $reservasiCheckedIn->count();

        return view('checkin.index', compact(
            'reservasiPending',
            'reservasiCheckedIn',
            'totalConfirmed',
            'totalCheckedIn'
        ));
    }

    // ─────────────────────────────────────────────
    // SHOW: Form check-in (PBI-34)
    // ─────────────────────────────────────────────
    public function show($id)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Confirmed')
            ->findOrFail($id);

        return view('checkin.show', compact('reservasi'));
    }

    // ─────────────────────────────────────────────
    // PROSES: Simpan check-in (PBI-34 & PBI-35)
    // ─────────────────────────────────────────────
    public function proses(Request $request, $id)
    {
        $validated = $request->validate([
            'tipe_identitas' => ['required', 'in:KTP,Paspor'],
            'no_identitas'   => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipe_identitas === 'KTP' && !preg_match('/^\d{16}$/', $value)) {
                        $fail('Nomor KTP harus terdiri dari tepat 16 digit angka.');
                    }
                },
            ],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'kontak'       => ['required', 'string', 'max:50'],
        ], [
            'tipe_identitas.required' => 'Tipe identitas wajib dipilih.',
            'tipe_identitas.in'       => 'Tipe identitas harus KTP atau Paspor.',
            'no_identitas.required'   => 'Nomor identitas wajib diisi.',
            'nama_lengkap.required'   => 'Nama lengkap wajib diisi.',
            'kontak.required'         => 'Nomor kontak wajib diisi.',
        ]);

        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Confirmed')
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            $reservasi->tamu->update([
                'tipe_identitas' => $validated['tipe_identitas'],
                'no_identitas'   => $validated['no_identitas'],
                'nama_lengkap'   => $validated['nama_lengkap'],
                'kontak'         => $validated['kontak'],
            ]);

            $reservasi->update(['status_reservasi' => 'Checked-In']);

            foreach ($reservasi->detailKamar as $detail) {
                $detail->kamar->update(['status_kamar' => 'Terisi']);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Proses check-in gagal: ' . $e->getMessage());
        }

        return redirect()->route('checkin.index')
            ->with('success', 'Check-in berhasil! Kamar telah diubah menjadi Terisi.');
    }

    // ─────────────────────────────────────────────
    // SHOW CHECKOUT: Preview tagihan (PBI-36 & 37)
    // ─────────────────────────────────────────────
    public function showCheckout($id)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar.tipeKamar'])
            ->where('status_reservasi', 'Checked-In')
            ->findOrFail($id);

        $kalkulasi = $this->hitungTagihan($reservasi->id_reservasi);

        return view('checkin.checkout', compact('reservasi', 'kalkulasi'));
    }

    // ─────────────────────────────────────────────
    // PROSES CHECKOUT: Simpan & update status
    // ─────────────────────────────────────────────
    public function prosesCheckout(Request $request, $id)
    {
        $reservasi = Reservasi::with(['detailKamar.kamar'])
            ->where('status_reservasi', 'Checked-In')
            ->findOrFail($id);

        $kalkulasi = $this->hitungTagihan($id);

        try {
            DB::beginTransaction();

            $reservasi->update([
                'status_reservasi' => 'Checked-Out',
                'biaya_kamar'      => $kalkulasi['biaya_kamar'],
                'jam_terlambat'    => $kalkulasi['jam_terlambat'],
                'biaya_charge'     => $kalkulasi['biaya_charge'],
                'total_tagihan'    => $kalkulasi['total_tagihan'],
            ]);

            foreach ($reservasi->detailKamar as $detail) {
                $detail->kamar->update(['status_kamar' => 'Kotor']);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Proses check-out gagal: ' . $e->getMessage());
        }

        return redirect()->route('checkin.struk', $id)
            ->with('success', 'Check-out berhasil! Silakan cetak struk pelunasan.');
    }

    // ─────────────────────────────────────────────
    // STRUK: Cetak struk pelunasan (PBI-37)
    // ─────────────────────────────────────────────
    public function struk($id)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar.tipeKamar'])
            ->where('status_reservasi', 'Checked-Out')
            ->findOrFail($id);

        return view('checkin.struk', compact('reservasi'));
    }

    // ─────────────────────────────────────────────
    // PRIVATE: Kalkulasi tagihan (PBI-36 & PBI-37)
    // Pakai DB::table() — tidak bergantung model tim lain
    // ─────────────────────────────────────────────
    private function hitungTagihan($id_reservasi): array
    {
        // Ambil data reservasi
        $reservasi = DB::table('reservasi')
            ->where('id_reservasi', $id_reservasi)
            ->first();

        // Hitung jumlah malam
        $tglCheckin  = Carbon::parse($reservasi->tgl_checkin)->startOfDay();
        $tglCheckout = Carbon::parse($reservasi->tgl_checkout)->startOfDay();
        $jumlahMalam = max(1, $tglCheckin->diffInDays($tglCheckout));

        // Ambil total harga dari semua kamar via DB::table()
        $hargaPerMalam = DB::table('detail_kamar')
            ->join('kamar', 'detail_kamar.id_kamar', '=', 'kamar.id_kamar')
            ->join('tipe_kamar', 'kamar.id_tipe', '=', 'tipe_kamar.id_tipe')
            ->where('detail_kamar.id_reservasi', $id_reservasi)
            ->sum('tipe_kamar.harga_per_malam');

        $biayaKamar = $hargaPerMalam * $jumlahMalam;

        // PBI-36: Cek keterlambatan — batas checkout pukul 12:00
        $batasCheckout = Carbon::parse($reservasi->tgl_checkout)->setTime(12, 0, 0);
        $sekarang      = Carbon::now();

        $jamTerlambat = 0;
        $biayaCharge  = 0;

        if ($sekarang->greaterThan($batasCheckout)) {
            $menitTerlambat = $batasCheckout->diffInMinutes($sekarang);
            $jamTerlambat   = (int) ceil($menitTerlambat / 60);
            // Charge: 10% harga/malam per jam terlambat
            $biayaCharge = $jamTerlambat * ($hargaPerMalam * 0.10);
        }

        // PBI-37: Total tagihan
        $totalTagihan = $biayaKamar + $biayaCharge;

        return [
            'jumlah_malam'  => $jumlahMalam,
            'harga_malam'   => $hargaPerMalam,
            'biaya_kamar'   => $biayaKamar,
            'batas_checkout'=> $batasCheckout,
            'sekarang'      => $sekarang,
            'jam_terlambat' => $jamTerlambat,
            'biaya_charge'  => $biayaCharge,
            'total_tagihan' => $totalTagihan,
        ];
    }
}