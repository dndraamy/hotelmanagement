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
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id('id_cuti');
            $table->foreignId('id_pegawai')->constrained('pegawai', 'id_pegawai');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->enum('status_approval', ['Pending', 'Approved', 'Rejected'])->default('Pending'); // Pending, Approved, Rejected 
            $table->foreignId('id_approver')->nullable()->constrained('pegawai', 'id_pegawai'); // Mengizinkan null sebelum di-approve 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
};
