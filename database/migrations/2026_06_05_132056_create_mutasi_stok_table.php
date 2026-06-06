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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->foreignId('id_barang')->constrained('barang', 'id_barang'); 
            $table->foreignId('id_supplier')->nullable()->constrained('supplier', 'id_supplier'); // Set nullable untuk mutasi keluar 
            $table->enum('jenis_mutasi', ['Masuk', 'Keluar']); // Masuk, Keluar 
            $table->integer('jumlah');
            $table->timestamp('tanggal_mutasi');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
