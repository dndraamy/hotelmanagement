<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiKasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transaksi_kas')->insert([
            // Sample data untuk transaksi_kas
            [
                'id_user' => 1,
                'tipe_transaksi' => 'Pemasukan',
                'kategori' => 'Pembayaran Kamar',
                'nominal' => 1500000.00,
                'tanggal_transaksi' => '2026-06-11',
                'keterangan' => 'Pembayaran kamar oleh tamu A',
                'bukti_nota_url' => null,
            ],
            [
                'id_user' => 1,
                'tipe_transaksi' => 'Pengeluaran',
                'kategori' => 'Gaji',
                'nominal' => 5000000.00,
                'tanggal_transaksi' => '2026-06-01',
                'keterangan' => 'Pembayaran gaji karyawan bulan Juni',
                'bukti_nota_url' => null,
            ],
            [
                'id_user' => 1,
                'tipe_transaksi' => 'Pemasukan',
                'kategori' => 'Pembayaran Restoran',
                'nominal' => 750000.00,
                'tanggal_transaksi' => '2026-06-10',
                'keterangan' => 'Pembayaran restoran oleh tamu B',
                'bukti_nota_url' => null,
            ],
        ]);
    }
}
