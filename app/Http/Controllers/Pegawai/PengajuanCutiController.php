<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use Illuminate\Support\Facades\Auth;

class PengajuanCutiController extends Controller
{
    // Menampilkan halaman form
    public function create()
    {
        return view('pegawai.form_pengajuan');
    }

    // Memproses data form ketika disubmit
    public function store(Request $request)
    {
        // 1. Validasi input dari pegawai
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'          => 'required|string',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih cepat dari tanggal mulai.',
        ]);

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // Cek keamanan: pastikan user memiliki id_pegawai
        if (!$user->id_pegawai) {
            return back()->with('error', 'Akun ini belum tertaut dengan data pegawai.');
        }

        // 3. Simpan data ke database
        PengajuanCuti::create([
            'id_pegawai'      => $user->id_pegawai,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan'          => $request->alasan,
            // status_approval otomatis 'Pending' karena bawaan default di database
            // id_approver otomatis null
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim dan menunggu persetujuan HRD.');
    }
}