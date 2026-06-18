<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiKasController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PenggabunganTagihanController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/kas/pemasukan', [TransaksiKasController::class, 'pemasukan'])->name('kas.pemasukan');
    Route::get('/kas/pengeluaran', [TransaksiKasController::class, 'pengeluaran'])->name('kas.pengeluaran');
    Route::post('/kas/store', [TransaksiKasController::class, 'store'])->name('kas.store');
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
    
  // ─── Penggabungan Tagihan ────────────────────────────────────────────────
  Route::get('/kas/penggabungan-tagihan', [PenggabunganTagihanController::class, 'index'])
    ->name('penggabungan-tagihan.index');

Route::post('/kas/penggabungan-tagihan/merge', [PenggabunganTagihanController::class, 'merge'])
    ->name('penggabungan-tagihan.merge');

Route::post('/kas/penggabungan-tagihan/unmerge', [PenggabunganTagihanController::class, 'unmerge'])
    ->name('penggabungan-tagihan.unmerge');
});
require __DIR__.'/auth.php';
