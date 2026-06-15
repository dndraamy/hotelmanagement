<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\HRD\JadwalShiftController;

Route::middleware(['auth', 'role:HRD'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::resource('jadwal-shift', JadwalShiftController::class)->except(['show']);
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
});

use App\Http\Controllers\Manajer\LaporanKeuanganController;

Route::middleware(['auth', 'role:Manajer Hotel'])
    ->prefix('manajer')
    ->name('manajer.')
    ->group(function () {

Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])
         ->name('laporan-keuangan.index');

Route::get(
    '/laporan-keuangan/export-pdf',
    [LaporanKeuanganController::class, 'exportPdf']
)->name('laporan-keuangan.export-pdf');
});

require __DIR__.'/auth.php';
