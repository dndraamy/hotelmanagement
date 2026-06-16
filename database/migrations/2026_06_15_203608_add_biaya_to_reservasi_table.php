<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->decimal('biaya_kamar', 12, 2)->default(0)->after('status_reservasi');
            $table->integer('jam_terlambat')->default(0)->after('biaya_kamar');
            $table->decimal('biaya_charge', 12, 2)->default(0)->after('jam_terlambat');
            $table->decimal('total_tagihan', 12, 2)->default(0)->after('biaya_charge');
        });
    }

    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn(['biaya_kamar', 'jam_terlambat', 'biaya_charge', 'total_tagihan']);
        });
    }
};