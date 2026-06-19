<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use Illuminate\Support\Facades\Auth;

class PengajuanCutiController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        $riwayatCuti = PengajuanCuti::where('id_pegawai', $user->id_pegawai)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('pegawai.form_pengajuan', compact('riwayatCuti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'          => 'required|string',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih cepat dari tanggal mulai.',
        ]);

        $user = Auth::user();

        if (!$user->id_pegawai) {
            return back()->with('error', 'Akun ini belum tertaut dengan data pegawai.');
        }

        PengajuanCuti::create([
            'id_pegawai'      => $user->id_pegawai,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan'          => $request->alasan,
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim dan status otomatis diperbarui di bawah.');
    }
}