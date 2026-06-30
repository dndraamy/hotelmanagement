<?php


use App\Models\Kamar;
use App\Models\TipeKamar;
use App\Models\User;

function buatTipeKamar(string $suffix = ''): TipeKamar
{
    return TipeKamar::create([
        'nama_tipe'      => 'TestTipe-' . $suffix . '-' . uniqid(),
        'harga_per_malam' => 300000,
    ]);
}

/**
 * Buat Kamar dengan status yang ditentukan.
 */
function buatKamar(TipeKamar $tipe, string $status, string $prefix = ''): Kamar
{
    return Kamar::create([
        'nomor_kamar'  => $prefix . rand(100, 899),
        'lantai'       => 1,
        'id_tipe'      => $tipe->id_tipe,
        'status_kamar' => $status,
    ]);
}

// ============================================================================
// TC-INT-01: Alur lengkap – kamar kotor tampil, lalu hilang setelah ditandai bersih
// ============================================================================

test('[PBI-43-INT-01] alur lengkap housekeeping: kamar kotor tampil di daftar, lalu hilang setelah ditandai bersih', function () {

    // Arrange: login sebagai Petugas Kebersihan
    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    $tipe       = buatTipeKamar('INT01');
    $kamarKotor = Kamar::create([
        'nomor_kamar'  => '303',
        'lantai'       => 3,
        'id_tipe'      => $tipe->id_tipe,
        'status_kamar' => 'Kotor',
    ]);
    Kamar::create([
        'nomor_kamar'  => '101',
        'lantai'       => 1,
        'id_tipe'      => $tipe->id_tipe,
        'status_kamar' => 'Bersih',
    ]);

    // Assert – PBI-40: halaman awal menampilkan 303, TIDAK 101
    $halamanAwal = $this->get('/housekeeping');
    $halamanAwal->assertOk();
    $halamanAwal->assertSee('303');
    $halamanAwal->assertDontSee('101');

    // Act – PBI-41: tandai 303 sebagai bersih
    $update = $this->post("/housekeeping/{$kamarKotor->id_kamar}/bersih");
    $update->assertRedirect(route('housekeeping.index'));
    $update->assertSessionHas('success');

    // Assert: database berubah
    $this->assertDatabaseHas('kamar', [
        'id_kamar'     => $kamarKotor->id_kamar,
        'status_kamar' => 'Bersih',
    ]);

    // Assert: halaman setelah update TIDAK lagi menampilkan 303
    $halamanSetelah = $this->get('/housekeeping');
    $halamanSetelah->assertOk();
    $halamanSetelah->assertDontSee('303');
});

// ============================================================================
// TC-INT-02: Kamar tidak ditemukan mengembalikan 404
// ============================================================================

test('[PBI-43-INT-02] menandai kamar yang tidak ada mengembalikan 404', function () {

    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    $response = $this->post('/housekeeping/99999/bersih');

    $response->assertNotFound();
});

// ============================================================================
// TC-INT-03: Hanya kamar yang ditandai yang berubah (multi-kamar)
// ============================================================================

test('[PBI-43-INT-03] hanya kamar yang ditandai yang berubah statusnya', function () {

    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    $tipe   = buatTipeKamar('INT03');
    $kamarA = buatKamar($tipe, 'Kotor', 'A');
    $kamarB = buatKamar($tipe, 'Kotor', 'B');

    // Tandai hanya kamar A
    $this->post("/housekeeping/{$kamarA->id_kamar}/bersih");

    // Kamar A → Bersih
    $this->assertDatabaseHas('kamar', [
        'id_kamar'     => $kamarA->id_kamar,
        'status_kamar' => 'Bersih',
    ]);

    // Kamar B → masih Kotor
    $this->assertDatabaseHas('kamar', [
        'id_kamar'     => $kamarB->id_kamar,
        'status_kamar' => 'Kotor',
    ]);
});

// ============================================================================
// TC-INT-04: Halaman tidak dapat diakses tanpa login (unauthenticated)
// ============================================================================

test('[PBI-43-INT-04] halaman housekeeping tidak dapat diakses tanpa login', function () {

    $tipe  = buatTipeKamar('INT04');
    $kamar = buatKamar($tipe, 'Kotor');

    // GET tanpa auth → redirect ke login
    $this->get('/housekeeping')->assertRedirect('/login');

    // POST tanpa auth → redirect ke login
    $this->post("/housekeeping/{$kamar->id_kamar}/bersih")->assertRedirect('/login');
});

// ============================================================================
// TC-INT-05: Halaman tampil OK meski tidak ada kamar kotor (empty state)
// ============================================================================

test('[PBI-43-INT-05] halaman housekeeping tampil normal saat tidak ada kamar kotor', function () {

    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    // Tidak ada kamar Kotor di database (RefreshDatabase sudah bersih)
    $response = $this->get('/housekeeping');

    $response->assertOk();
    // Tidak boleh error / crash
    $response->assertStatus(200);
});

// ============================================================================
// TC-INT-06: Filter ketat – kamar dengan status lain (Kosong, Terisi) tidak tampil
// ============================================================================

test('[PBI-43-INT-06] kamar berstatus Kosong dan Terisi tidak tampil di daftar housekeeping', function () {

    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    $tipe       = buatTipeKamar('INT06');
    $kamarKosong = Kamar::create([
        'nomor_kamar'  => '201',
        'lantai'       => 2,
        'id_tipe'      => $tipe->id_tipe,
        'status_kamar' => 'Kosong',
    ]);
    $kamarTerisi = Kamar::create([
        'nomor_kamar'  => '202',
        'lantai'       => 2,
        'id_tipe'      => $tipe->id_tipe,
        'status_kamar' => 'Terisi',
    ]);
    $kamarKotor = Kamar::create([
        'nomor_kamar'  => '203',
        'lantai'       => 2,
        'id_tipe'      => $tipe->id_tipe,
        'status_kamar' => 'Kotor',
    ]);

    $response = $this->get('/housekeeping');
    $response->assertOk();

    // Kamar 203 (Kotor) tampil
    $response->assertSee('203');

    // Kamar 201 (Kosong) dan 202 (Terisi) tidak tampil
    $response->assertDontSee('201');
    $response->assertDontSee('202');
});

// ============================================================================
// TC-INT-07: Update status mengembalikan flash message sukses
// ============================================================================

test('[PBI-43-INT-07] update status kamar mengembalikan flash message sukses', function () {

    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    $tipe  = buatTipeKamar('INT07');
    $kamar = buatKamar($tipe, 'Kotor');

    $response = $this->post("/housekeeping/{$kamar->id_kamar}/bersih");

    // Harus redirect ke halaman index housekeeping
    $response->assertRedirect(route('housekeeping.index'));

    // Session harus mengandung key 'success'
    $response->assertSessionHas('success');
});

// ============================================================================
// TC-INT-08: Update status berkali-kali pada kamar yang sama (idempotent)
// ============================================================================

test('[PBI-43-INT-08] menandai kamar bersih berkali-kali tetap konsisten', function () {

    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    $tipe  = buatTipeKamar('INT08');
    $kamar = buatKamar($tipe, 'Kotor');

    // Tandai pertama kali
    $this->post("/housekeeping/{$kamar->id_kamar}/bersih")->assertRedirect(route('housekeeping.index'));

    // Tandai lagi (misalnya karena double-click atau refresh)
    $response = $this->post("/housekeeping/{$kamar->id_kamar}/bersih");

    // Tidak boleh error — status tetap Bersih
    $response->assertRedirect(route('housekeeping.index'));
    $this->assertDatabaseHas('kamar', [
        'id_kamar'     => $kamar->id_kamar,
        'status_kamar' => 'Bersih',
    ]);
});

// ============================================================================
// TC-INT-09: Integrasi route – method POST diterima, GET ditolak (405)
// ============================================================================

test('[PBI-43-INT-09] endpoint tandai bersih hanya menerima POST bukan GET', function () {

    $petugas = User::role('Petugas Kebersihan')->first();
    $this->actingAs($petugas);

    $tipe  = buatTipeKamar('INT09');
    $kamar = buatKamar($tipe, 'Kotor');

    // GET ke endpoint POST harus 405 Method Not Allowed
    $response = $this->get("/housekeeping/{$kamar->id_kamar}/bersih");
    $response->assertStatus(405);
});