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
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->string('nomor_kamar')->unique();
            $table->integer('lantai');
            $table->foreignId('id_tipe')->constrained('tipe_kamar', 'id_tipe');
            $table->enum('status_kamar', [
    'Kosong',           // Siap huni, sudah bersih
    'Terisi',           // Sedang ditempati tamu
    'Kotor',            // Tamu sudah checkout, perlu dibersihkan  
    'Bersih'            // Sudah dibersihkan housekeeping, siap huni lagi
]); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
