<?php

namespace Database\Seeders;

use App\Models\ItemMenu;
use Illuminate\Database\Seeder;

class ItemMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi data contoh menu makanan & minuman untuk modul POS Restoran (PBI-44).
     */
    public function run(): void
    {
        $items = [
            ['nama_item' => 'Nasi Goreng Spesial', 'kategori' => 'Makanan', 'harga' => 45000],
            ['nama_item' => 'Mie Goreng Seafood', 'kategori' => 'Makanan', 'harga' => 48000],
            ['nama_item' => 'Ayam Bakar Madu', 'kategori' => 'Makanan', 'harga' => 55000],
            ['nama_item' => 'Sate Ayam (10 tusuk)', 'kategori' => 'Makanan', 'harga' => 40000],
            ['nama_item' => 'Sandwich Club', 'kategori' => 'Makanan', 'harga' => 38000],
            ['nama_item' => 'Sup Krim Jagung', 'kategori' => 'Makanan', 'harga' => 28000],
            ['nama_item' => 'Es Teh Manis', 'kategori' => 'Minuman', 'harga' => 12000],
            ['nama_item' => 'Es Jeruk', 'kategori' => 'Minuman', 'harga' => 15000],
            ['nama_item' => 'Kopi Hitam', 'kategori' => 'Minuman', 'harga' => 18000],
            ['nama_item' => 'Cappuccino', 'kategori' => 'Minuman', 'harga' => 28000],
            ['nama_item' => 'Jus Alpukat', 'kategori' => 'Minuman', 'harga' => 25000],
            ['nama_item' => 'Mineral Water 600ml', 'kategori' => 'Minuman', 'harga' => 10000],
        ];

        foreach ($items as $item) {
            ItemMenu::firstOrCreate(
                ['nama_item' => $item['nama_item']],
                $item
            );
        }
    }
}
