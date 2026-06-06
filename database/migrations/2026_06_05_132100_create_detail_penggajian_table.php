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
        Schema::create('detail_penggajian', function (Blueprint $table) {
            $table->id('id_detail_penggajian');
            $table->foreignId('id_penggajian')->constrained('penggajian', 'id_penggajian'); 
            $table->foreignId('id_komponen')->constrained('komponen_gaji', 'id_komponen'); 
            $table->decimal('nominal_terapan', 12, 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penggajian');
    }
};
