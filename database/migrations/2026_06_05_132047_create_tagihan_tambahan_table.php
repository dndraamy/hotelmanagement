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
        Schema::create('tagihan_tambahan', function (Blueprint $table) {
            $table->id('id_tagihan_tambahan');
            $table->foreignId('id_reservasi')->constrained('reservasi', 'id_reservasi'); 
            $table->foreignId('id_biaya')->constrained('master_biaya_tambahan', 'id_biaya'); 
            $table->decimal('nominal_akhir', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_tambahan');
    }
};
