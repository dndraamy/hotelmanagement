<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Masukkan data master minimal untuk FK tabel users
        DB::table('divisi')->insertOrIgnore([
            'id_divisi' => 1,
            'nama_divisi' => 'Manajemen',
        ]);

        DB::table('jabatan')->insertOrIgnore([
            'id_jabatan' => 1,
            'nama_jabatan' => 'Admin Utama',
            'gaji_pokok' => 5000000,
        ]);

        DB::table('pegawai')->insertOrIgnore([
            'id_pegawai' => 1,
            'nama_lengkap' => 'Pegawai Tester',
            'kontak' => '08123456789',
            'alamat' => 'Alamat Tester',
            'id_divisi' => 1,
            'id_jabatan' => 1,
        ]);

        // Buat roles menggunakan Spatie Permission
        $roles = [
            'Super Admin',
            'Manajer Hotel',
            'Resepsionis',
            'Petugas Kebersihan',
            'Staf Keuangan',
            'Staf HRD',
            'Karyawan',
            'Staf Gudang',
            'Petugas Restoran',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Buat user Super Admin dan assign role
        $admin = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'id_pegawai' => 1,
                'email' => 'admin@hotel.test',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('Super Admin');
        
        // Seed data menu makanan & minuman untuk modul POS Restoran (PBI-44)
        $this->call(ItemMenuSeeder::class);
    }
}