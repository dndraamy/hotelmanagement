<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tamu;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Reservasi;
use App\Models\DetailKamar;
use App\Models\ItemMenu;
use App\Models\PesananRestoran;
use App\Models\DetailPesananRestoran;
use App\Models\MasterBiayaTambahan;
use App\Models\TagihanTambahan;
use App\Models\Tagihan;

class MockHotelDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        Tamu::truncate();
        TipeKamar::truncate();
        Kamar::truncate();
        Reservasi::truncate();
        DetailKamar::truncate();
        ItemMenu::truncate();
        PesananRestoran::truncate();
        DetailPesananRestoran::truncate();
        MasterBiayaTambahan::truncate();
        TagihanTambahan::truncate();
        Tagihan::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Tipe Kamar
        $tipeStandard = TipeKamar::create([
            'nama_tipe' => 'Standard Room',
            'harga_per_malam' => 500000.00
        ]);

        $tipeDeluxe = TipeKamar::create([
            'nama_tipe' => 'Deluxe Suite',
            'harga_per_malam' => 900000.00
        ]);

        $tipeSuite = TipeKamar::create([
            'nama_tipe' => 'Executive Suite',
            'harga_per_malam' => 1500000.00
        ]);

        // 2. Kamar
        $kamar101 = Kamar::create([
            'nomor_kamar' => '101',
            'lantai' => 1,
            'id_tipe' => $tipeStandard->id_tipe,
            'status_kamar' => 'Kosong'
        ]);

        $kamar102 = Kamar::create([
            'nomor_kamar' => '102',
            'lantai' => 1,
            'id_tipe' => $tipeStandard->id_tipe,
            'status_kamar' => 'Kosong'
        ]);

        $kamar201 = Kamar::create([
            'nomor_kamar' => '201',
            'lantai' => 2,
            'id_tipe' => $tipeDeluxe->id_tipe,
            'status_kamar' => 'Terisi'
        ]);

        $kamar301 = Kamar::create([
            'nomor_kamar' => '301',
            'lantai' => 3,
            'id_tipe' => $tipeSuite->id_tipe,
            'status_kamar' => 'Terisi'
        ]);

        // 3. Tamu
        $tamu1 = Tamu::create([
            'tipe_identitas' => 'KTP',
            'no_identitas' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'kontak' => '081234567890'
        ]);

        $tamu2 = Tamu::create([
            'tipe_identitas' => 'Paspor',
            'no_identitas' => 'A987654321',
            'nama_lengkap' => 'Jane Smith',
            'kontak' => '089876543210'
        ]);

        // 4. Reservasi
        // John Doe stays from June 15 to June 18 (3 nights)
        $reservasiJohn = Reservasi::create([
            'id_tamu' => $tamu1->id_tamu,
            'tanggal_reservasi' => '2026-06-14 10:00:00',
            'tgl_checkin' => '2026-06-15',
            'tgl_checkout' => '2026-06-18',
            'status_reservasi' => 'Checked-In'
        ]);

        DetailKamar::create([
            'id_reservasi' => $reservasiJohn->id_reservasi,
            'id_kamar' => $kamar201->id_kamar
        ]);

        // Jane Smith stayed from June 10 to June 12 (2 nights)
        $reservasiJane = Reservasi::create([
            'id_tamu' => $tamu2->id_tamu,
            'tanggal_reservasi' => '2026-06-09 14:00:00',
            'tgl_checkin' => '2026-06-10',
            'tgl_checkout' => '2026-06-12',
            'status_reservasi' => 'Checked-Out'
        ]);

        DetailKamar::create([
            'id_reservasi' => $reservasiJane->id_reservasi,
            'id_kamar' => $kamar301->id_kamar
        ]);

        // 5. Item Menu
        $nasigoreng = ItemMenu::create([
            'nama_item' => 'Nasi Goreng Spesial',
            'kategori' => 'Makanan',
            'harga' => 35000.00
        ]);

        $miegoreng = ItemMenu::create([
            'nama_item' => 'Mie Goreng Jawa',
            'kategori' => 'Makanan',
            'harga' => 30000.00
        ]);

        $esteh = ItemMenu::create([
            'nama_item' => 'Es Teh Manis',
            'kategori' => 'Minuman',
            'harga' => 8000.00
        ]);

        $jusalpukat = ItemMenu::create([
            'nama_item' => 'Jus Alpukat',
            'kategori' => 'Minuman',
            'harga' => 15000.00
        ]);

        // 6. Pesanan Restoran POS
        // Order 1 for John Doe (Linked to Reservation but not merged yet)
        $pesananJohn1 = PesananRestoran::create([
            'id_reservasi' => $reservasiJohn->id_reservasi,
            'tanggal_pesanan' => '2026-06-15 13:00:00',
            'total_harga' => 51000.00,
            'status_pembayaran' => 'Belum Lunas',
            'status_pesanan' => 'Selesai'
        ]);

        DetailPesananRestoran::create([
            'id_pesanan' => $pesananJohn1->id_pesanan,
            'id_item' => $nasigoreng->id_item,
            'qty' => 1,
            'subtotal' => 35000.00
        ]);

        DetailPesananRestoran::create([
            'id_pesanan' => $pesananJohn1->id_pesanan,
            'id_item' => $esteh->id_item,
            'qty' => 2,
            'subtotal' => 16000.00
        ]);

        // Order 2 for John Doe (Linked to Reservation but not merged yet)
        $pesananJohn2 = PesananRestoran::create([
            'id_reservasi' => $reservasiJohn->id_reservasi,
            'tanggal_pesanan' => '2026-06-16 19:30:00',
            'total_harga' => 45000.00,
            'status_pembayaran' => 'Belum Lunas',
            'status_pesanan' => 'Selesai'
        ]);

        DetailPesananRestoran::create([
            'id_pesanan' => $pesananJohn2->id_pesanan,
            'id_item' => $miegoreng->id_item,
            'qty' => 1,
            'subtotal' => 30000.00
        ]);

        DetailPesananRestoran::create([
            'id_pesanan' => $pesananJohn2->id_pesanan,
            'id_item' => $jusalpukat->id_item,
            'qty' => 1,
            'subtotal' => 15000.00
        ]);

        // Order 3 (Walk-in restaurant guest - Unlinked POS order)
        $pesananWalkIn = PesananRestoran::create([
            'id_reservasi' => null,
            'tanggal_pesanan' => '2026-06-16 20:00:00',
            'total_harga' => 100000.00,
            'status_pembayaran' => 'Belum Lunas',
            'status_pesanan' => 'Selesai'
        ]);

        DetailPesananRestoran::create([
            'id_pesanan' => $pesananWalkIn->id_pesanan,
            'id_item' => $nasigoreng->id_item,
            'qty' => 2,
            'subtotal' => 70000.00
        ]);

        DetailPesananRestoran::create([
            'id_pesanan' => $pesananWalkIn->id_pesanan,
            'id_item' => $jusalpukat->id_item,
            'qty' => 2,
            'subtotal' => 30000.00
        ]);

        // 7. Master Biaya Tambahan
        $extrabed = MasterBiayaTambahan::create([
            'nama_biaya' => 'Extra Bed',
            'nominal_default' => 150000.00
        ]);

        $latecheckout = MasterBiayaTambahan::create([
            'nama_biaya' => 'Late Check-Out',
            'nominal_default' => 200000.00
        ]);

        // 8. Tagihan Tambahan
        TagihanTambahan::create([
            'id_reservasi' => $reservasiJohn->id_reservasi,
            'id_biaya' => $extrabed->id_biaya,
            'nominal_akhir' => 150000.00
        ]);

        // 9. Tagihan (Main Bills)
        // John Doe: Room Deluxe 900,000 * 3 nights = 2,700,000. Tambahan = 150,000. Restoran = 0 initially.
        Tagihan::create([
            'id_reservasi' => $reservasiJohn->id_reservasi,
            'total_kamar' => 2700000.00,
            'total_restoran' => 0.00,
            'total_tambahan' => 150000.00,
            'grand_total' => 2850000.00,
            'status_tagihan' => 'Belum Lunas'
        ]);

        // Jane Smith: Room Suite 1,500,000 * 2 nights = 3,000,000. Tambahan = 0. Restoran = 0.
        Tagihan::create([
            'id_reservasi' => $reservasiJane->id_reservasi,
            'total_kamar' => 3000000.00,
            'total_restoran' => 0.00,
            'total_tambahan' => 0.00,
            'grand_total' => 3000000.00,
            'status_tagihan' => 'Belum Lunas'
        ]);
    }
}
