<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel room_charges menyimpan tagihan manual yang dibebankan langsung ke kamar tamu.
     * Berbeda dengan tagihan_tambahan (terikat master_biaya), room_charges bersifat fleksibel.
     */
    public function up(): void
    {
        Schema::create('room_charges', function (Blueprint $table) {
            $table->id('id_room_charge');
            $table->foreignId('id_reservasi')->constrained('reservasi', 'id_reservasi')->onDelete('cascade');
            $table->string('deskripsi', 255);           // Deskripsi tagihan bebas teks
            $table->decimal('nominal', 12, 2);          // Nominal dalam rupiah
            $table->enum('kategori', [                  // Kategori tagihan
                'F&B',
                'Minibar',
                'Spa',
                'Miscellaneous',
            ])->default('Miscellaneous');
            $table->text('catatan')->nullable();         // Catatan tambahan opsional
            $table->string('charged_by')->nullable();   // Nama/email petugas yang menginput
            $table->enum('status', ['Pending', 'Settled', 'Void'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_charges');
    }
};
