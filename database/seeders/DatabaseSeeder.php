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
        // ==================================
        // MASTER DATA
        // ==================================

        DB::table('divisi')->insertOrIgnore([
            'id_divisi' => 1,
            'nama_divisi' => 'Manajemen',
        ]);

         DB::table('jabatan')->insertOrIgnore([
            ['id_jabatan' => 1, 'nama_jabatan' => 'Admin Utama', 'gaji_pokok' => 5000000],
            ['id_jabatan' => 2, 'nama_jabatan' => 'Manajer Hotel', 'gaji_pokok' => 8000000],
            ['id_jabatan' => 3, 'nama_jabatan' => 'Resepsionis', 'gaji_pokok' => 3500000],
            ['id_jabatan' => 4, 'nama_jabatan' => 'Petugas Kebersihan', 'gaji_pokok' => 3000000],
            ['id_jabatan' => 5, 'nama_jabatan' => 'Staf Keuangan', 'gaji_pokok' => 4500000],
            ['id_jabatan' => 6, 'nama_jabatan' => 'Staf HRD', 'gaji_pokok' => 4500000],
            ['id_jabatan' => 7, 'nama_jabatan' => 'Karyawan', 'gaji_pokok' => 3000000],
            ['id_jabatan' => 8, 'nama_jabatan' => 'Staf Gudang', 'gaji_pokok' => 3500000],
            ['id_jabatan' => 9, 'nama_jabatan' => 'Petugas Restoran', 'gaji_pokok' => 3500000],
        ]);

        DB::table('pegawai')->insertOrIgnore([
            [
                'id_pegawai' => 1,
                'nama_lengkap' => 'Pegawai Admin',
                'kontak' => '08123456789',
                'alamat' => 'Alamat Admin',
                'id_divisi' => 1,
                'id_jabatan' => 1,
            ],
            [
                'id_pegawai' => 2,
                'nama_lengkap' => 'Staf HRD',
                'kontak' => '08987654321',
                'alamat' => 'Alamat HRD',
                'id_divisi' => 1,
                'id_jabatan' => 1,
            ],
        ]);

        // ==================================
        // ROLES
        // ==================================

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
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        // ==================================
        // SUPER ADMIN
        // ==================================

        $admin = User::firstOrCreate(
            [
                'username' => 'superadmin'
            ],
            [
                'id_pegawai' => 1,
                'email' => 'admin@hotel.test',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $admin->assignRole('Super Admin');

        // ==================================
        // PEGAWAI (ALL ROLE)
        // ==================================

        $pegawaiList = [];
        for ($i = 1; $i <= count($roles); $i++) {
            $pegawaiList[] = [
                'id_pegawai' => $i,
                'nama_lengkap' => 'Pegawai ' . ($i == 1 ? 'Super Admin' : $roles[$i - 1]),
                'kontak' => '081234567' . $i,
                'alamat' => 'Alamat ' . ($i == 1 ? 'Super Admin' : $roles[$i - 1]),
                'id_divisi' => 1,
                'id_jabatan' => $i,
            ];
        }
        DB::table('pegawai')->insertOrIgnore($pegawaiList);

        for ($i = 1; $i < count($roles); $i++) {
            $roleName = $roles[$i];
            $username = strtolower(str_replace(' ', '', $roleName));
            $email = $username . '@hotel.test';

            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'id_pegawai' => $i + 1,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]
            );

            $user->assignRole($roleName);
        }

        // ==================================
        // DUMMY SUPPLIER
        // ==================================

        DB::table('supplier')->insertOrIgnore([
            [
                'id_supplier' => 1,
                'nama_supplier' => 'PT Sumber Bersih',
                'kontak' => '081111111111',
                'alamat' => 'Jakarta',
            ],
            [
                'id_supplier' => 2,
                'nama_supplier' => 'PT Hotel Supplies',
                'kontak' => '082222222222',
                'alamat' => 'Bandung',
            ],
            [
                'id_supplier' => 3,
                'nama_supplier' => 'PT Prima Laundry',
                'kontak' => '083333333333',
                'alamat' => 'Surabaya',
            ],
        ]);

        // ==================================
        // DUMMY BARANG
        // ==================================

        DB::table('barang')->insertOrIgnore([
            [
                'id_barang' => 1,
                'nama_barang' => 'Handuk Hotel',
                'kategori' => 'Housekeeping',
                'satuan' => 'pcs',
                'stok_sekarang' => 45,
                'stok_minimal' => 20,
            ],
            [
                'id_barang' => 2,
                'nama_barang' => 'Sabun Mandi',
                'kategori' => 'Amenities',
                'satuan' => 'pcs',
                'stok_sekarang' => 12,
                'stok_minimal' => 15,
            ],
            [
                'id_barang' => 3,
                'nama_barang' => 'Shampoo',
                'kategori' => 'Amenities',
                'satuan' => 'botol',
                'stok_sekarang' => 35,
                'stok_minimal' => 10,
            ],
            [
                'id_barang' => 4,
                'nama_barang' => 'Tisu',
                'kategori' => 'Housekeeping',
                'satuan' => 'pack',
                'stok_sekarang' => 25,
                'stok_minimal' => 10,
            ],
            [
                'id_barang' => 5,
                'nama_barang' => 'Air Mineral',
                'kategori' => 'F&B',
                'satuan' => 'dus',
                'stok_sekarang' => 50,
                'stok_minimal' => 20,
            ],
        ]);

        // ==================================
        // DUMMY MUTASI STOK
        // ==================================

        DB::table('mutasi_stok')->insertOrIgnore([
            [
                'id_barang' => 1,
                'id_supplier' => 1,
                'jenis_mutasi' => 'masuk',
                'jumlah' => 30,
                'tanggal_mutasi' => now(),
                'keterangan' => 'Pengadaan awal stok hotel',
            ],
            [
                'id_barang' => 2,
                'id_supplier' => 2,
                'jenis_mutasi' => 'keluar',
                'jumlah' => 15,
                'tanggal_mutasi' => now(),
                'keterangan' => 'Digunakan housekeeping',
            ],
            [
                'id_barang' => 3,
                'id_supplier' => 2,
                'jenis_mutasi' => 'masuk',
                'jumlah' => 20,
                'tanggal_mutasi' => now(),
                'keterangan' => 'Restock bulanan',
            ],
        ]);
    }
}
