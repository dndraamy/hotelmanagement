<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan_restoran', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_reservasi')->nullable()->constrained('reservasi', 'id_reservasi'); // Ditambahkan nullable agar tidak error 
            $table->timestamp('tanggal_pesanan');
            $table->decimal('total_harga', 12, 2);
            $table->string('status_pembayaran'); // Lunas, Charge to Room 
            $table->string('status_pesanan'); // Pending, Cooking, Selesai 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_restoran');
    }
};
