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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id('id_penggajian');
            $table->foreignId('id_pegawai')->constrained('pegawai', 'id_pegawai'); 
            $table->integer('periode_bulan');
            $table->integer('periode_tahun');
            $table->decimal('total_gaji_pokok', 12, 2);
            $table->decimal('total_uang_lembur', 12, 2);
            $table->decimal('total_potongan', 12, 2);
            $table->decimal('gaji_bersih', 12, 2);
            $table->timestamp('tanggal_cetak_slip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
