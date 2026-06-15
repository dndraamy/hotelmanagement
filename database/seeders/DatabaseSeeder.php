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
