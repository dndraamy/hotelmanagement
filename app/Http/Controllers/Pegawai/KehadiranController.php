<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\JadwalPegawai;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index()
    {
        $idPegawai = auth()->user()->id_pegawai;

        $jadwalHariIni = JadwalPegawai::with('shift')
            ->where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', today())
            ->first();

        $hariIni = Kehadiran::where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', today())
            ->first();

        $riwayat = Kehadiran::where('id_pegawai', $idPegawai)
            ->latest('tanggal')
            ->paginate(10);

        return view('pegawai.kehadiran.index', compact(
            'jadwalHariIni',
            'hariIni',
            'riwayat'
        ));
    }

    public function checkIn()
    {
        $idPegawai = auth()->user()->id_pegawai;
        $tanggal = today();

        $existing = Kehadiran::where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah check in hari ini.');
        }

        $status = 'Hadir';

        $jadwal = JadwalPegawai::with('shift')
            ->where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($jadwal && $jadwal->shift) {

            $jamMulai = Carbon::parse($jadwal->shift->jam_mulai);

            if (now()->gt($jamMulai)) {
                $status = 'Terlambat';
            }
        }

        Kehadiran::create([
            'id_pegawai' => $idPegawai,
            'tanggal' => $tanggal,
            'jam_masuk' => now()->format('H:i:s'),
            'jam_pulang' => null,
            'status_kehadiran' => $status,
            'menit_lembur' => 0,
        ]);

        return back()->with('success', 'Check in berhasil.');
    }

    public function checkOut()
    {
        $idPegawai = auth()->user()->id_pegawai;

        $kehadiran = Kehadiran::where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', today())
            ->first();

        if (!$kehadiran) {
            return back()->with('error', 'Silakan check in terlebih dahulu.');
        }

        if ($kehadiran->jam_pulang) {
            return back()->with('error', 'Anda sudah check out.');
        }

        $menitLembur = 0;

        $jadwal = JadwalPegawai::with('shift')
            ->where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', today())
            ->first();

        if ($jadwal && $jadwal->shift) {

            $jamSelesai = Carbon::parse($jadwal->shift->jam_selesai);

            if (now()->gt($jamSelesai)) {
                $menitLembur = $jamSelesai->diffInMinutes(now());
            }
        }

        $kehadiran->update([
            'jam_pulang' => now()->format('H:i:s'),
            'menit_lembur' => $menitLembur,
        ]);

        return back()->with('success', 'Check out berhasil.');
    }
}