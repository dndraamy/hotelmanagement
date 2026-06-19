<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::with('pegawai');

        if ($request->filled('id_pegawai')) {
            $query->where('id_pegawai', $request->id_pegawai);
        }

        if ($request->filled('status')) {
            $query->where('status_kehadiran', $request->status);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $kehadiran = $query
            ->latest('tanggal')
            ->paginate(15)
            ->withQueryString();

        $pegawais = Pegawai::orderBy('nama_lengkap')->get();

        return view('dashboard.hrd.kehadiran.index', compact(
            'kehadiran',
            'pegawais'
        ));
    }
}