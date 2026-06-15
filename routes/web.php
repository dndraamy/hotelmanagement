<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Pegawai\KehadiranController as PegawaiKehadiranController;
use App\Http\Controllers\HRD\KehadiranController as HRDKehadiranController;
use App\Http\Controllers\HRD\ApprovalCutiController;
use App\Http\Controllers\Pegawai\PengajuanCutiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/inventory', [InventoryController::class, 'index'])
        ->name('inventory.index');

    Route::get('/inventory/mutasi', [InventoryController::class, 'mutasi'])
        ->name('inventory.mutasi');

    Route::post('/inventory/masuk', [InventoryController::class, 'barangMasuk'])
        ->name('inventory.masuk');

    Route::post('/inventory/keluar', [InventoryController::class, 'barangKeluar'])
        ->name('inventory.keluar');

    Route::get('/inventory/laporan', [InventoryController::class, 'laporan'])
        ->name('inventory.laporan');

    Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
        Route::get('/', [PegawaiKehadiranController::class, 'index'])->name('index');
        Route::post('/checkin', [PegawaiKehadiranController::class, 'checkIn'])->name('checkin');
        Route::post('/checkout', [PegawaiKehadiranController::class, 'checkOut'])->name('checkout');
    });

    Route::get('/pegawai/form_pengajuan', [PengajuanCutiController::class, 'create'])->name('pegawai.cuti.create');
    Route::post('/pegawai/form_pengajuan', [PengajuanCutiController::class, 'store'])->name('pegawai.cuti.store');

    Route::prefix('hrd')->name('hrd.')->group(function () {
        Route::get('/kehadiran', [HRDKehadiranController::class, 'index'])->name('kehadiran.index');
        Route::get('/cuti',                           [ApprovalCutiController::class, 'index'])->name('cuti.index');
        Route::get('/cuti/{pengajuanCuti}',           [ApprovalCutiController::class, 'show'])->name('cuti.show');
        Route::patch('/cuti/{pengajuanCuti}/approve', [ApprovalCutiController::class, 'approve'])->name('cuti.approve');
        Route::patch('/cuti/{pengajuanCuti}/reject',  [ApprovalCutiController::class, 'reject'])->name('cuti.reject');
    });

});
require __DIR__.'/auth.php';
 