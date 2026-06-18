<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\RoomCharge;
use Illuminate\Http\Request;

class RoomChargeController extends Controller
{
    /**
     * Tampilkan halaman terminal Room Charge.
     * Menyediakan daftar tamu In-House (Checked-In) untuk dipilih.
     */
    public function index(Request $request)
    {
        // Ambil tamu yang sedang Checked-In beserta kamar mereka
        $tamuCheckedIn = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('status_reservasi', 'Checked-In')
            ->latest()
            ->get()
            ->map(function ($reservasi) {
                $nomorKamar = $reservasi->detailKamar
                    ->map(fn($dk) => $dk->kamar?->nomor_kamar)
                    ->filter()
                    ->implode(', ');

                return (object)[
                    'id_reservasi' => $reservasi->id_reservasi,
                    'nama_tamu'    => $reservasi->tamu?->nama_lengkap ?? 'Tamu Tidak Diketahui',
                    'nomor_kamar'  => $nomorKamar ?: 'N/A',
                    'tgl_checkin'  => $reservasi->tgl_checkin,
                ];
            });

        return view('room-charge.index', compact('tamuCheckedIn'));
    }

    /**
     * Simpan tagihan baru ke room_charges.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_reservasi' => 'required|exists:reservasi,id_reservasi',
            'deskripsi'    => 'required|string|max:255',
            'nominal'      => 'required|numeric|min:1',
            'kategori'     => 'required|in:F&B,Minibar,Spa,Miscellaneous',
            'catatan'      => 'nullable|string|max:1000',
        ], [
            'id_reservasi.required' => 'Mohon pilih tamu terlebih dahulu.',
            'id_reservasi.exists'   => 'Reservasi tidak ditemukan.',
            'deskripsi.required'    => 'Deskripsi tagihan wajib diisi.',
            'nominal.required'      => 'Nominal wajib diisi.',
            'nominal.min'           => 'Nominal harus lebih dari 0.',
            'kategori.required'     => 'Kategori wajib dipilih.',
        ]);

        // Pastikan reservasi memang Checked-In
        $reservasi = Reservasi::with(['tamu', 'detailKamar.kamar'])
            ->where('id_reservasi', $request->id_reservasi)
            ->where('status_reservasi', 'Checked-In')
            ->first();

        if (!$reservasi) {
            return redirect()->back()
                ->with('error', 'Tamu yang dipilih sudah tidak aktif atau belum Check-In.')
                ->withInput();
        }

        $nomorKamar = $reservasi->detailKamar
            ->map(fn($dk) => $dk->kamar?->nomor_kamar)
            ->filter()
            ->implode(', ');

        $namaTamu = $reservasi->tamu?->nama_lengkap ?? 'Tamu';

        RoomCharge::create([
            'id_reservasi' => $request->id_reservasi,
            'deskripsi'    => $request->deskripsi,
            'nominal'      => (float) str_replace(['.', ','], ['', '.'], $request->nominal),
            'kategori'     => $request->kategori,
            'catatan'      => $request->catatan,
            'charged_by'   => auth()->user()?->name ?? auth()->user()?->email ?? 'System',
            'status'       => 'Pending',
        ]);

        return redirect()->route('room-charge.index')
            ->with('success', "✓ Tagihan berhasil dibebankan ke Kamar {$nomorKamar} atas nama {$namaTamu}.");
    }
}
