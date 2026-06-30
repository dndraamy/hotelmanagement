<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJadwalShiftRequest;
use App\Http\Requests\UpdateJadwalShiftRequest;
use App\Models\JadwalPegawai;
use App\Models\Pegawai;
use App\Models\Shift;
use Illuminate\Http\Request;

class JadwalShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalPegawai::with(['pegawai', 'shift']);

        $bulan = $request->filled('bulan') ? $request->bulan : now()->month;
        $tahun = $request->filled('tahun') ? $request->tahun : now()->year;

        $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);

        if ($request->filled('id_pegawai')) {
            $query->where('id_pegawai', $request->id_pegawai);
        }

        $jadwals  = $query->orderBy('tanggal')->get();
        $pegawais = Pegawai::orderBy('nama_lengkap')->get();
        $shifts   = Shift::all();

        return view('dashboard.hrd.jadwal-shift.index', compact('jadwals', 'pegawais', 'shifts', 'bulan', 'tahun'));
    }

    public function create()
    {
        $pegawais = Pegawai::orderBy('nama_lengkap')->get();
        $shifts   = Shift::all();
        return view('dashboard.hrd.jadwal-shift.create', compact('pegawais', 'shifts'));
    }

    public function store(StoreJadwalShiftRequest $request)
    {
        $sudahAda = JadwalPegawai::where('id_pegawai', $request->id_pegawai)
                        ->where('tanggal', $request->tanggal)
                        ->exists();

        if ($sudahAda) {
            return back()
                ->withErrors(['tanggal' => 'Pegawai ini sudah memiliki jadwal pada tanggal tersebut.'])
                ->withInput();
        }

        JadwalPegawai::create($request->only(['id_pegawai', 'id_shift', 'tanggal']));

        return redirect()->route('dashboard.hrd.jadwal-shift.index')
                         ->with('success', 'Jadwal shift berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $jadwal   = JadwalPegawai::findOrFail($id);
        $pegawais = Pegawai::orderBy('nama_lengkap')->get();
        $shifts   = Shift::all();
        return view('dashboard.hrd.jadwal-shift.edit', compact('jadwal', 'pegawais', 'shifts'));
    }

    public function update(UpdateJadwalShiftRequest $request, string $id)
    {
        $jadwal = JadwalPegawai::findOrFail($id);

        $sudahAda = JadwalPegawai::where('id_pegawai', $request->id_pegawai)
                        ->where('tanggal', $request->tanggal)
                        ->where('id_jadwal', '!=', $id)
                        ->exists();

        if ($sudahAda) {
            return back()
                ->withErrors(['tanggal' => 'Pegawai ini sudah memiliki jadwal pada tanggal tersebut.'])
                ->withInput();
        }

        $jadwal->update($request->only(['id_pegawai', 'id_shift', 'tanggal']));

        return redirect()->route('dashboard.hrd.jadwal-shift.index')
                         ->with('success', 'Jadwal shift berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        JadwalPegawai::findOrFail($id)->delete();

        return redirect()->route('dashboard.hrd.jadwal-shift.index')
                         ->with('success', 'Jadwal shift berhasil dihapus.');
    }
}
