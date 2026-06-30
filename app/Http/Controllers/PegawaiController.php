<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        $pegawais = Pegawai::with(['divisi', 'jabatan'])->orderBy('nama_lengkap')->get();
        return view('dashboard.hrd.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();

        return view('dashboard.hrd.pegawai.create', compact('divisi', 'jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kontak' => 'required|string|max:20',
            'alamat' => 'required|string',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        Pegawai::create($validated);

        return redirect()->route('hrd.dashboard.hrd.pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();
        
        return view('dashboard.hrd.pegawai.edit', compact('pegawai', 'divisi', 'jabatan'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kontak' => 'required|string|max:20',
            'alamat' => 'required|string',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        $pegawai->update($validated);

        return redirect()->route('hrd.dashboard.hrd.pegawai.index')->with('success', 'Data pegawai berhasil diubah.');
    }

    public function destroy(Pegawai $pegawai)
    {
        DB::transaction(function () use ($pegawai) {
            $pegawai->user()->delete();
            $pegawai->jadwalPegawai()->delete();
            $pegawai->kehadiran()->delete();
            $pegawai->pengajuanCuti()->delete();
            $pegawai->penggajian()->delete();
            $pegawai->delete();
        });

        return redirect()->route('hrd.dashboard.hrd.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
