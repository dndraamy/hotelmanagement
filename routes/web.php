<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\HRD\JadwalShiftController;
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


Route::middleware(['auth'])->prefix('hrd')->name('hrd.')->group(function () {
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

    Route::get('/pegawai', [\App\Http\Controllers\PegawaiController::class, 'index'])
        ->name('pegawai.index');
    Route::get('/pegawai/create', [\App\Http\Controllers\PegawaiController::class, 'create'])
        ->name('pegawai.create');
    Route::post('/pegawai', [\App\Http\Controllers\PegawaiController::class, 'store'])
        ->name('pegawai.store');
    Route::get('/pegawai/{pegawai}/edit', [\App\Http\Controllers\PegawaiController::class, 'edit'])
        ->name('pegawai.edit');
    Route::put('/pegawai/{pegawai}', [\App\Http\Controllers\PegawaiController::class, 'update'])
        ->name('pegawai.update');
    Route::delete('/pegawai/{pegawai}', [\App\Http\Controllers\PegawaiController::class, 'destroy'])
        ->name('pegawai.destroy');
});
require __DIR__.'/auth.php';
