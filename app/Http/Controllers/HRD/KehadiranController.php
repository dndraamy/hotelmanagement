<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\JadwalPegawai;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index()
    {
        $idPegawai = auth()->user()->id_pegawai;

        $riwayat = Kehadiran::where('id_pegawai', $idPegawai)
            ->latest('tanggal')
            ->paginate(10);

        $hariIni = Kehadiran::where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', now())
            ->first();

        return view('hrd.kehadiran.index', compact(
            'riwayat',
            'hariIni'
        ));
    }

    public function checkIn()
    {
        $idPegawai = auth()->user()->id_pegawai;

        $tanggal = now()->toDateString();

        $existing = Kehadiran::where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($existing) {
            return back()->with(
                'error',
                'Anda sudah melakukan check-in hari ini.'
            );
        }

        $status = 'Hadir';

        $jadwal = JadwalPegawai::with('shift')
            ->where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($jadwal && $jadwal->shift) {

            $jamShift = Carbon::parse(
                $jadwal->shift->jam_mulai
            );

            $jamSekarang = now();

            if ($jamSekarang->gt($jamShift)) {
                $status = 'Terlambat';
            }
        }

        Kehadiran::create([
            'id_pegawai' => $idPegawai,
            'tanggal' => $tanggal,
            'jam_masuk' => now()->format('H:i:s'),
            'status_kehadiran' => $status,
            'menit_lembur' => 0,
        ]);

        return back()->with(
            'success',
            'Check-in berhasil.'
        );
    }

    public function checkOut()
    {
        $idPegawai = auth()->user()->id_pegawai;

        $kehadiran = Kehadiran::where(
            'id_pegawai',
            $idPegawai
        )
        ->whereDate('tanggal', now())
        ->first();

        if (!$kehadiran) {
            return back()->with(
                'error',
                'Silakan check-in terlebih dahulu.'
            );
        }

        if ($kehadiran->jam_pulang) {
            return back()->with(
                'error',
                'Anda sudah check-out.'
            );
        }

        $menitLembur = 0;

        $jadwal = JadwalPegawai::with('shift')
            ->where('id_pegawai', $idPegawai)
            ->whereDate('tanggal', now())
            ->first();

        if ($jadwal && $jadwal->shift) {

            $jamSelesaiShift = Carbon::parse(
                $jadwal->shift->jam_selesai
            );

            $jamSekarang = now();

            if ($jamSekarang->gt($jamSelesaiShift)) {

                $menitLembur =
                    $jamSelesaiShift
                        ->diffInMinutes(
                            $jamSekarang
                        );
            }
        }

        $kehadiran->update([
            'jam_pulang' => now()->format('H:i:s'),
            'menit_lembur' => $menitLembur,
        ]);

        return back()->with(
            'success',
            'Check-out berhasil.'
        );
    }
}