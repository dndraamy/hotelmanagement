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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_tagihan')->nullable()->constrained('tagihan', 'id_tagihan'); // Set nullable untuk transaksi DP 
            $table->foreignId('id_reservasi')->constrained('reservasi', 'id_reservasi'); 
            $table->string('jenis_pembayaran'); // Uang Muka (DP), Pelunasan 
            $table->string('metode_bayar'); // Transfer, Tunai 
            $table->decimal('nominal', 12, 2);
            $table->timestamp('tanggal_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
