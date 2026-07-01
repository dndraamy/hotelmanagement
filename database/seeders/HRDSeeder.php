<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HrdSeeder extends Seeder
{
    public function run(): void
    {
        // ==================================
        // SHIFT
        // ==================================

        DB::table('shift')->insertOrIgnore([
            [
                'id_shift' => 1,
                'nama_shift' => 'Pagi',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '15:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_shift' => 2,
                'nama_shift' => 'Siang',
                'jam_mulai' => '15:00:00',
                'jam_selesai' => '23:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_shift' => 3,
                'nama_shift' => 'Malam',
                'jam_mulai' => '23:00:00',
                'jam_selesai' => '07:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==================================
        // JADWAL PEGAWAI
        // ==================================

        // id_pegawai: 3 = Resepsionis, 4 = Petugas Kebersihan, 7 = Karyawan, 9 = Petugas Restoran
        $pegawaiShift = [
            3 => 1, // Resepsionis -> Shift Pagi
            4 => 2, // Petugas Kebersihan -> Shift Siang
            7 => 1, // Karyawan -> Shift Pagi
            9 => 3, // Petugas Restoran -> Shift Malam
        ];

        $startDate = Carbon::now()->startOfWeek();
        $jadwalData = [];

        foreach ($pegawaiShift as $idPegawai => $idShift) {
            for ($i = 0; $i < 7; $i++) {
                $jadwalData[] = [
                    'id_pegawai' => $idPegawai,
                    'id_shift' => $idShift,
                    'tanggal' => $startDate->copy()->addDays($i)->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('jadwal_pegawai')->insertOrIgnore($jadwalData);

        // ==================================
        // KEHADIRAN
        // ==================================

        $kehadiranData = [
            [
                'id_pegawai' => 3,
                'tanggal' => $startDate->copy()->addDays(0)->toDateString(),
                'jam_masuk' => '06:55:00',
                'jam_pulang' => '15:05:00',
                'status_kehadiran' => 'Hadir',
                'menit_lembur' => 0,
            ],
            [
                'id_pegawai' => 3,
                'tanggal' => $startDate->copy()->addDays(1)->toDateString(),
                'jam_masuk' => '07:20:00',
                'jam_pulang' => '15:00:00',
                'status_kehadiran' => 'Terlambat',
                'menit_lembur' => 0,
            ],
            [
                'id_pegawai' => 4,
                'tanggal' => $startDate->copy()->addDays(0)->toDateString(),
                'jam_masuk' => '14:55:00',
                'jam_pulang' => '23:45:00',
                'status_kehadiran' => 'Hadir',
                'menit_lembur' => 45,
            ],
            [
                'id_pegawai' => 7,
                'tanggal' => $startDate->copy()->addDays(2)->toDateString(),
                'jam_masuk' => null,
                'jam_pulang' => null,
                'status_kehadiran' => 'Alpha',
                'menit_lembur' => 0,
            ],
            [
                'id_pegawai' => 9,
                'tanggal' => $startDate->copy()->addDays(1)->toDateString(),
                'jam_masuk' => '22:50:00',
                'jam_pulang' => '08:10:00',
                'status_kehadiran' => 'Hadir',
                'menit_lembur' => 70,
            ],
        ];

        foreach ($kehadiranData as &$row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }
        unset($row);

        DB::table('kehadiran')->insertOrIgnore($kehadiranData);

        // ==================================
        // PENGAJUAN CUTI (+ APPROVAL HRD)
        // ==================================

        // id_pegawai 6 = Staf HRD, bertindak sebagai approver
        DB::table('pengajuan_cuti')->insertOrIgnore([
            [
                'id_pegawai' => 3,
                'tanggal_mulai' => Carbon::now()->addDays(5)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addDays(7)->toDateString(),
                'alasan' => 'Acara keluarga',
                'status_approval' => 'Pending',
                'id_approver' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pegawai' => 4,
                'tanggal_mulai' => Carbon::now()->addDays(2)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addDays(2)->toDateString(),
                'alasan' => 'Sakit',
                'status_approval' => 'Pending',
                'id_approver' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pegawai' => 9,
                'tanggal_mulai' => Carbon::now()->addDays(10)->toDateString(),
                'tanggal_selesai' => Carbon::now()->addDays(11)->toDateString(),
                'alasan' => 'Keperluan pribadi',
                'status_approval' => 'Pending',
                'id_approver' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}