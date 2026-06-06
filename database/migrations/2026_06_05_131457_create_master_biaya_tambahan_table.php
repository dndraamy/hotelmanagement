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
        Schema::create('master_biaya_tambahan', function (Blueprint $table) {
            $table->id('id_biaya');
            $table->string('nama_biaya'); // Late Check-Out, Kerusakan 
            $table->decimal('nominal_default', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_biaya_tambahan');
    }
};
