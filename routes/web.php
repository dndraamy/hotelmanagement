<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosRestoranController;
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

    // ─── POS Restoran ────────────────────────────────────────────────────────
    Route::prefix('pos-restoran')->name('pos-restoran.')->group(function () {
        // Halaman utama POS
        Route::get('/', [PosRestoranController::class, 'index'])->name('index');

        // API: Daftar tamu Checked-In (untuk dropdown dinamis)
        Route::get('/api/tamu-checkedin', [PosRestoranController::class, 'getCheckedInGuests'])->name('tamu-checkedin');

        // Buat pesanan baru dari keranjang
        Route::post('/buat-pesanan', [PosRestoranController::class, 'buatPesanan'])->name('buat-pesanan');

        // Charge to Room: update status_pembayaran dan id_reservasi
        Route::patch('/{id_pesanan}/charge-to-room', [PosRestoranController::class, 'chargeToRoom'])->name('charge-to-room');
    });
});

require __DIR__.'/auth.php';
