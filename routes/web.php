<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\HRD\ApprovalCutiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/jadwal', function () {
    return view('jadwal.index');
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

    Route::prefix('hrd')->name('hrd.')->group(function () {
        Route::get('/cuti',                           [ApprovalCutiController::class, 'index'])->name('cuti.index');
        Route::get('/cuti/{pengajuanCuti}',           [ApprovalCutiController::class, 'show'])->name('cuti.show');
        Route::patch('/cuti/{pengajuanCuti}/approve', [ApprovalCutiController::class, 'approve'])->name('cuti.approve');
        Route::patch('/cuti/{pengajuanCuti}/reject',  [ApprovalCutiController::class, 'reject'])->name('cuti.reject');
    });

});
require __DIR__.'/auth.php';
 