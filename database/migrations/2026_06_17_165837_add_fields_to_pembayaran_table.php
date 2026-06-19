<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran', 'nomor_referensi')) {
                $table->string('nomor_referensi', 100)->nullable()->after('metode_bayar');
            }

            if (!Schema::hasColumn('pembayaran', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('nomor_referensi');
            }

            if (!Schema::hasColumn('pembayaran', 'status')) {
                $table->string('status', 50)->default('Lunas')->after('bukti_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['nomor_referensi', 'bukti_pembayaran', 'status']);
        });
    }
};
