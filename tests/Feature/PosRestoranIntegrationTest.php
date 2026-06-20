<?php

use App\Models\User;
use App\Models\ItemMenu;
use App\Models\Reservasi;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Models\DetailKamar;
use App\Models\PesananRestoran;
use App\Models\TipeKamar;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup Role & User
    \Spatie\Permission\Models\Role::firstOrCreate([
        'name' => 'Petugas Restoran',
        'guard_name' => 'web'
    ]);
    $this->petugas = User::factory()->create();
    $this->petugas->assignRole('Petugas Restoran');

    // Setup Menu
    $this->menu1 = ItemMenu::create([
        'nama_item' => 'Nasi Goreng Spesial',
        'kategori'  => 'Makanan',
        'harga'     => 45000,
        'status'    => 'Tersedia'
    ]);
    
    $this->menu2 = ItemMenu::create([
        'nama_item' => 'Es Teh Manis',
        'kategori'  => 'Minuman',
        'harga'     => 15000,
        'status'    => 'Tersedia'
    ]);
});

test('[PBI-47-INT-01] berhasil membuat pesanan baru dari keranjang', function () {
    $this->actingAs($this->petugas);

    $payload = [
        'items' => [
            ['id_item' => $this->menu1->id_item, 'qty' => 2],
            ['id_item' => $this->menu2->id_item, 'qty' => 1]
        ]
    ];

    $response = $this->post(route('pos-restoran.buat-pesanan'), $payload);

    $response->assertRedirect();
    $response->assertSessionHas('info');

    $this->assertDatabaseHas('pesanan_restoran', [
        'total_harga' => (45000 * 2) + 15000,
        'status_pembayaran' => 'Belum Dibayar',
        'status_pesanan' => 'Pending'
    ]);

    $pesanan = PesananRestoran::latest('id_pesanan')->first();

    $this->assertDatabaseHas('detail_pesanan_restoran', [
        'id_pesanan' => $pesanan->id_pesanan,
        'id_item' => $this->menu1->id_item,
        'qty' => 2,
        'subtotal' => 90000
    ]);
});

test('[PBI-47-INT-02] gagal membuat pesanan jika keranjang kosong atau qty tidak valid', function () {
    $this->actingAs($this->petugas);

    // Payload kosong
    $response1 = $this->post(route('pos-restoran.buat-pesanan'), []);
    $response1->assertSessionHasErrors(['items']);

    // Payload qty tidak valid
    $response2 = $this->post(route('pos-restoran.buat-pesanan'), [
        'items' => [
            ['id_item' => $this->menu1->id_item, 'qty' => 0]
        ]
    ]);
    $response2->assertSessionHasErrors(['items.0.qty']);
});

test('[PBI-47-INT-03] berhasil menagihkan pesanan ke kamar tamu (Checked-In)', function () {
    $this->actingAs($this->petugas);

    // Buat Tamu, Kamar, Reservasi
    $tamu = Tamu::create([
        'nama_lengkap' => 'Budi Santoso',
        'tipe_identitas' => 'KTP',
        'no_identitas' => '1234567890',
        'kontak' => '0811111111'
    ]);

    $tipeKamar = TipeKamar::create([
        'nama_tipe' => 'Deluxe',
        'harga_per_malam' => 500000
    ]);

    $kamar = Kamar::create([
        'nomor_kamar' => '101',
        'lantai' => 1,
        'id_tipe' => $tipeKamar->id_tipe,
        'status_kamar' => 'Terisi'
    ]);

    $reservasi = Reservasi::create([
        'id_tamu' => $tamu->id_tamu,
        'tanggal_reservasi' => now(),
        'tgl_checkin' => now()->subDay(),
        'tgl_checkout' => now()->addDay(),
        'jumlah_tamu' => 1,
        'status_reservasi' => 'Checked-In'
    ]);

    DetailKamar::create([
        'id_reservasi' => $reservasi->id_reservasi,
        'id_kamar' => $kamar->id_kamar
    ]);

    // Buat pesanan
    $pesanan = PesananRestoran::create([
        'id_reservasi' => null,
        'tanggal_pesanan' => now(),
        'total_harga' => 60000,
        'status_pembayaran' => 'Belum Dibayar',
        'status_pesanan' => 'Pending'
    ]);

    $payload = [
        'id_reservasi' => $reservasi->id_reservasi
    ];

    $response = $this->patch(route('pos-restoran.charge-to-room', $pesanan->id_pesanan), $payload);

    $response->assertRedirect(route('pos-restoran.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pesanan_restoran', [
        'id_pesanan' => $pesanan->id_pesanan,
        'id_reservasi' => $reservasi->id_reservasi,
        'status_pembayaran' => 'Charge to Room'
    ]);
});

test('[PBI-47-INT-04] gagal charge to room jika kamar tidak Checked-In', function () {
    $this->actingAs($this->petugas);

    $tamu = Tamu::create([
        'nama_lengkap' => 'Andi',
        'tipe_identitas' => 'KTP',
        'no_identitas' => '12345',
        'kontak' => '081'
    ]);

    $reservasi = Reservasi::create([
        'id_tamu' => $tamu->id_tamu,
        'tanggal_reservasi' => now(),
        'tgl_checkin' => now()->addDay(),
        'tgl_checkout' => now()->addDays(2),
        'jumlah_tamu' => 1,
        'status_reservasi' => 'Confirmed' // Bukan Checked-In
    ]);

    $pesanan = PesananRestoran::create([
        'tanggal_pesanan' => now(),
        'total_harga' => 50000,
        'status_pembayaran' => 'Belum Dibayar',
        'status_pesanan' => 'Pending'
    ]);

    $response = $this->patch(route('pos-restoran.charge-to-room', $pesanan->id_pesanan), [
        'id_reservasi' => $reservasi->id_reservasi
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('pesanan_restoran', [
        'id_pesanan' => $pesanan->id_pesanan,
        'id_reservasi' => null,
        'status_pembayaran' => 'Belum Dibayar'
    ]);
});

test('[PBI-47-INT-05] gagal jika pesanan sudah di-charge sebelumnya (mencegah double charge)', function () {
    $this->actingAs($this->petugas);

    $tamu = Tamu::create(['nama_lengkap' => 'Test', 'tipe_identitas' => 'KTP', 'no_identitas' => '1', 'kontak' => '1']);
    $reservasi = Reservasi::create([
        'id_tamu' => $tamu->id_tamu,
        'tanggal_reservasi' => now(),
        'tgl_checkin' => now(),
        'tgl_checkout' => now()->addDay(),
        'jumlah_tamu' => 1,
        'status_reservasi' => 'Checked-In'
    ]);

    $pesanan = PesananRestoran::create([
        'id_reservasi' => $reservasi->id_reservasi,
        'tanggal_pesanan' => now(),
        'total_harga' => 50000,
        'status_pembayaran' => 'Charge to Room', // Sudah charge
        'status_pesanan' => 'Pending'
    ]);

    $response = $this->patch(route('pos-restoran.charge-to-room', $pesanan->id_pesanan), [
        'id_reservasi' => $reservasi->id_reservasi
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('[PBI-47-INT-06] endpoint tidak dapat diakses tanpa autentikasi', function () {
    $this->post(route('pos-restoran.buat-pesanan'), [])->assertRedirect('/login');
    $this->patch(route('pos-restoran.charge-to-room', 1), [])->assertRedirect('/login');
});
