<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\JadwalPegawai;
use App\Models\Pegawai;
use App\Models\Divisi;
use Illuminate\Http\Request;

class JadwalKerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalPegawai::with([
            'pegawai.divisi',
            'pegawai.jabatan',
            'shift'
        ]);

        // Filter pegawai
        if ($request->filled('id_pegawai')) {
            $query->where('id_pegawai', $request->id_pegawai);
        }

        // Filter divisi
        if ($request->filled('id_divisi')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('id_divisi', $request->id_divisi);
            });
        }

        $jadwals = $query
            ->orderBy('tanggal', 'asc')
            ->get();

        $pegawais = Pegawai::orderBy('nama_lengkap')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('dashboard.hrd.jadwal-kerja.index', compact(
            'jadwals',
            'pegawais',
            'divisis'
        ));
    }
}