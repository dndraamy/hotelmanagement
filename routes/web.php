<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiKasController; 
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

Route::middleware(['auth', 'role:Staf Keuangan|Manajer Hotel'])->group(function () {
    Route::get('/keuangan/transaksi', [TransaksiKasController::class, 'index'])->name('transaksi.index');
    Route::post('/keuangan/transaksi', [TransaksiKasController::class, 'store'])->name('transaksi.store');
});

require __DIR__.'/auth.php';