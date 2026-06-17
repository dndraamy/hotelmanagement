<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\InventoryController;
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
    // ──── Profile ───────────────────────────────────────────────────────
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ──── Reservasi (PB-05) ─────────────────────────────────────────────
    // PBI-31: Daftar & Detail
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/{id}', [ReservasiController::class, 'show'])->name('reservasi.show');
    Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');

    // PBI-31: Edit & Update Reservasi
    Route::get('/reservasi/{id}/edit', [ReservasiController::class, 'edit'])->name('reservasi.edit');
    Route::put('/reservasi/{id}', [ReservasiController::class, 'update'])->name('reservasi.update');

    // PBI-28: Pencarian Kamar
    Route::get('/reservasi/cari/kamar', [ReservasiController::class, 'cariKamar'])->name('reservasi.cari-kamar');

    // PBI-29: Pembuatan Reservasi
    Route::get('/reservasi/buat/baru', [ReservasiController::class, 'buatReservasi'])->name('reservasi.buat');
    Route::post('/reservasi/simpan', [ReservasiController::class, 'simpanReservasi'])->name('reservasi.simpan-reservasi');

    // PBI-30: Pembayaran DP
    Route::get('/reservasi/{id}/pembayaran-dp', [ReservasiController::class, 'formPembayaranDP'])->name('reservasi.pembayaran-dp');
    Route::post('/reservasi/{id}/simpan-dp', [ReservasiController::class, 'simpanPembayaranDP'])->name('reservasi.simpan-dp');

    // ──── Inventory ─────────────────────────────────────────────────────
    Route::get('/inventory',         [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/mutasi',  [InventoryController::class, 'mutasi'])->name('inventory.mutasi');
    Route::post('/inventory/masuk',  [InventoryController::class, 'barangMasuk'])->name('inventory.masuk');
    Route::post('/inventory/keluar', [InventoryController::class, 'barangKeluar'])->name('inventory.barangKeluar');
    Route::get('/inventory/laporan', [InventoryController::class, 'laporan'])->name('inventory.laporan');

    // ──── Resepsionis – Check-In & Check-Out ──────────────────────────
    Route::get('/checkin',                       [CheckInController::class, 'index'])->name('checkin.index');
    Route::get('/checkin/{id}',                  [CheckInController::class, 'show'])->name('checkin.show');
    Route::post('/checkin/{id}/proses',          [CheckInController::class, 'proses'])->name('checkin.proses');
    Route::get('/checkin/{id}/checkout',         [CheckInController::class, 'showCheckout'])->name('checkin.checkout');
    Route::post('/checkin/{id}/checkout/proses', [CheckInController::class, 'prosesCheckout'])->name('checkin.prosesCheckout');
    Route::get('/checkin/{id}/struk',            [CheckInController::class, 'struk'])->name('checkin.struk');
});

require __DIR__ . '/auth.php';