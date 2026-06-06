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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id('id_tagihan');
            $table->foreignId('id_reservasi')->constrained('reservasi', 'id_reservasi'); 
            $table->decimal('total_kamar', 12, 2);
            $table->decimal('total_restoran', 12, 2)->default(0); 
            $table->decimal('total_tambahan', 12, 2)->default(0); 
            $table->decimal('grand_total', 12, 2);
            $table->enum('status_tagihan', ['Belum Lunas', 'Lunas'])->default('Belum Lunas'); // Belum Lunas, Lunas 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
