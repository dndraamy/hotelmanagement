<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\CheckInController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ──── Reservasi (PB-05) ────────────────────────────────────────────────
    Route::get('/reservasi/cari-kamar', [ReservasiController::class, 'cariKamar'])->name('reservasi.cari-kamar');
    Route::resource('reservasi', ReservasiController::class);

    // ──── Inventory ────────────────────────────────────────────────────────
    Route::get('/inventory',         [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/mutasi',  [InventoryController::class, 'mutasi'])->name('inventory.mutasi');
    Route::post('/inventory/masuk',  [InventoryController::class, 'barangMasuk'])->name('inventory.masuk');
    Route::post('/inventory/keluar', [InventoryController::class, 'barangKeluar'])->name('inventory.barangKeluar');
    Route::get('/inventory/laporan', [InventoryController::class, 'laporan'])->name('inventory.laporan');

    // ──── Resepsionis – Check-In & Check-Out (PB-06 / PBI-34 & PBI-35) ────
    Route::get('/checkin',                      [CheckInController::class, 'index'])->name('checkin.index');
    Route::get('/checkin/{id}',                 [CheckInController::class, 'show'])->name('checkin.show');
    Route::post('/checkin/{id}/proses',         [CheckInController::class, 'proses'])->name('checkin.proses');
    Route::post('/checkin/{id}/checkout',       [CheckInController::class, 'checkout'])->name('checkin.checkout');

});

require __DIR__ . '/auth.php';