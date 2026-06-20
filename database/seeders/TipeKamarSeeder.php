<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipeKamar;

class TipeKamarSeeder extends Seeder
{
    public function run(): void
    {
        TipeKamar::create(['nama' => 'Standard', 'harga' => 500000]);
        TipeKamar::create(['nama' => 'Deluxe', 'harga' => 800000]);
        TipeKamar::create(['nama' => 'Suite', 'harga' => 1200000]);
        TipeKamar::create(['nama' => 'Family', 'harga' => 1000000]);
        TipeKamar::create(['nama' => 'President Suite', 'harga' => 2500000]);
    }
}
