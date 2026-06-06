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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nama_lengkap');
            $table->string('kontak');
            $table->text('alamat');
            // Relasi menggunakan foreignId agar sinkron dengan bigint unsigned
            $table->foreignId('id_divisi')->constrained('divisi', 'id_divisi'); 
            $table->foreignId('id_jabatan')->constrained('jabatan', 'id_jabatan'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
