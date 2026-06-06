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
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id('id_reservasi');
            $table->foreignId('id_tamu')->constrained('tamu', 'id_tamu'); 
            $table->timestamp('tanggal_reservasi');
            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
            $table->enum('status_reservasi', ['Menunggu DP', 'Confirmed', 'Checked-In', 'Checked-Out', 'Cancelled']); // Menunggu DP, Confirmed, Checked-In, Checked-Out, Cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
