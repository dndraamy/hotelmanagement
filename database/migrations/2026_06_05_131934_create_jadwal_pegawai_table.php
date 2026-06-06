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
        Schema::create('jadwal_pegawai', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->foreignId('id_pegawai')->constrained('pegawai', 'id_pegawai'); 
            $table->foreignId('id_shift')->constrained('shift', 'id_shift'); 
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pegawai');
    }
};
