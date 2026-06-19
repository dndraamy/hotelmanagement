<?php

use App\Models\Kamar;
use App\Models\TipeKamar;
use App\Models\User;

test('status kamar dapat diubah menjadi bersih', function () {

    $user = User::first();

    $this->actingAs($user);

    $tipe = TipeKamar::create([
        'nama_tipe' => 'Standard',
        'harga_per_malam' => 300000,
    ]);

    $kamar = Kamar::create([
        'nomor_kamar' => '999',
        'lantai' => 1,
        'id_tipe' => $tipe->id_tipe,
        'status_kamar' => 'Kotor',
    ]);

   $this->post("/housekeeping/{$kamar->id_kamar}/bersih");


    $this->assertDatabaseHas('kamar', [
        'id_kamar' => $kamar->id_kamar,
        'status_kamar' => 'Bersih',
    ]);
});