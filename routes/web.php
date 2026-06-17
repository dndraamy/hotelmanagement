<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
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

// Route Reservasi (PBI-28 : Pencarian Kamar)
Route::get('/reservasi/cari-kamar', [ReservasiController::class, 'cariKamar'])->name('reservasi.cari-kamar');
Route::post('/reservasi/cari-kamar', [ReservasiController::class, 'prosesCariKamar'])->name('reservasi.proses-cari');

// Route Reservasi (PBI-29 : Pembuatan Reservasi)
Route::get('/reservasi/buat', [ReservasiController::class, 'buatReservasi'])->name('reservasi.buat');
Route::post('/reservasi/simpan', [ReservasiController::class, 'simpanReservasi'])->name('reservasi.simpan-reservasi');

// Route Reservasi (PBI-30 : Pembayaran DP)
Route::get('/reservasi/pembayaran-dp/{id}', [ReservasiController::class, 'formPembayaranDP'])->name('reservasi.pembayaran-dp');
Route::post('/reservasi/simpan-dp/{id}', [ReservasiController::class, 'simpanPembayaranDP'])->name('reservasi.simpan-dp');

require __DIR__.'/auth.php';
