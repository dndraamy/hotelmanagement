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
        Schema::create('detail_kamar', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_reservasi')->constrained('reservasi', 'id_reservasi'); 
            $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kamar');
    }
};
