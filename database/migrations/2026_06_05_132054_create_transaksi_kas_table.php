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
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->enum('tipe_transaksi', ['Pemasukan', 'Pengeluaran']); // Pemasukan, Pengeluaran 
            $table->string('kategori'); // Pembayaran Kamar, Operasional, Gaji 
            $table->decimal('nominal', 12, 2);
            $table->date('tanggal_transaksi');
            $table->text('keterangan');
            $table->string('bukti_nota_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_kas');
    }
};
