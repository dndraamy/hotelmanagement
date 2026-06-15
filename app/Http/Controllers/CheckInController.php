<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kamar;
use App\Models\Reservasi;
use App\Models\DetailKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    // Menampilkan dua tab: Pending (siap check-in) & Checked-In (siap check-out)
    public function index()
    {
        // Reservasi menunggu check-in (status: 'Confirmed')
        $reservasiPending = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Confirmed')
            ->orderBy('tgl_checkin', 'asc')
            ->get();

        // Reservasi yang sedang check-in (status: 'Checked-In')
        $reservasiCheckedIn = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Checked-In')
            ->orderBy('tgl_checkout', 'asc')
            ->get();

        // Summary counts untuk stat cards
        $totalConfirmed  = $reservasiPending->count();
        $totalCheckedIn  = $reservasiCheckedIn->count();

        return view('checkin.index', compact(
            'reservasiPending',
            'reservasiCheckedIn',
            'totalConfirmed',
            'totalCheckedIn'
        ));
    }

    // Tampilkan halaman form check-in untuk reservasi tertentu
    public function show($id)
    {
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Confirmed')
            ->findOrFail($id);

        return view('checkin.show', compact('reservasi'));
    }

    public function proses(Request $request, $id)
    {
        // Validasi
        $validated = $request->validate([
            'tipe_identitas' => ['required', 'in:KTP,Paspor'],
            'no_identitas'   => [
                'required',
                'string',
                'max:50',
                // Jika KTP, harus tepat 16 digit angka
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipe_identitas === 'KTP' && !preg_match('/^\d{16}$/', $value)) {
                        $fail('Nomor KTP harus terdiri dari tepat 16 digit angka.');
                    }
                },
            ],
            'nama_lengkap'   => ['required', 'string', 'max:100'],
            'kontak'         => ['required', 'string', 'max:50'],
        ], [
            'tipe_identitas.required' => 'Tipe identitas wajib dipilih.',
            'tipe_identitas.in'       => 'Tipe identitas harus KTP atau Paspor.',
            'no_identitas.required'   => 'Nomor identitas wajib diisi.',
            'no_identitas.max'        => 'Nomor identitas maksimal 50 karakter.',
            'nama_lengkap.required'   => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max'        => 'Nama lengkap maksimal 100 karakter.',
            'kontak.required'         => 'Nomor kontak wajib diisi.',
            'kontak.max'              => 'Nomor kontak maksimal 50 karakter.',
        ]);

        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Confirmed')
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            // Update data identitas tamu
            $reservasi->tamu->update([
                'tipe_identitas' => $validated['tipe_identitas'],
                'no_identitas'   => $validated['no_identitas'],
                'nama_lengkap'   => $validated['nama_lengkap'],
                'kontak'         => $validated['kontak'],
            ]);

            // Update status reservasi
            $reservasi->update(['status_reservasi' => 'Checked-In']);

            // Update status semua kamar terkait
            foreach ($reservasi->detailKamar as $detail) {
                $detail->kamar->update(['status_kamar' => 'Terisi']);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Proses check-in gagal. Silakan coba lagi. (' . $e->getMessage() . ')');
        }

        return redirect()
            ->route('checkin.index')
            ->with('success', 'Check-in berhasil! Kamar telah diubah menjadi Terisi.');
    }

    public function checkout($id)
    {
        $reservasi = Reservasi::with(['detailKamar.kamar'])
            ->where('status_reservasi', 'Checked-In')
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            // Update status reservasi = CheckedOut
            $reservasi->update(['status_reservasi' => 'Checked-Out']);

            // Update status semua kamar terkait = Kotor
            foreach ($reservasi->detailKamar as $detail) {
                $detail->kamar->update(['status_kamar' => 'Kotor']);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Proses check-out gagal. Silakan coba lagi. (' . $e->getMessage() . ')');
        }

        return redirect()
            ->route('checkin.index')
            ->with('success', 'Check-out berhasil! Kamar telah diubah menjadi Kotor dan siap dibersihkan.');
    }
}