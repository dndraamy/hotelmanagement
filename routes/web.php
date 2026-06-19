<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\HRD\JadwalShiftController;
use App\Http\Controllers\HRD\JadwalKerjaController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Role-based Dashboards
    Route::get('/dashboard/manager', function () {
        return view('dashboard');
    })->middleware('role:Manajer Hotel')->name('dashboard.manager');
    Route::get('/dashboard/receptionist', function () {
        return view('dashboard');
    })->middleware('role:Resepsionis')->name('dashboard.receptionist');
    Route::get('/dashboard/finance', function () {
        return view('dashboard');
    })->middleware('role:Staf Keuangan')->name('dashboard.finance');
    Route::get('/dashboard/restaurant', function () {
        return view('dashboard');
    })->middleware('role:Petugas Restoran')->name('dashboard.restaurant');
    Route::get('/dashboard/cleaning', function () {
        return view('dashboard');
    })->middleware('role:Petugas Kebersihan')->name('dashboard.cleaning');
    Route::get('/dashboard/hrd', function () {
        return view('dashboard');
    })->middleware('role:Staf HRD')->name('dashboard.hrd');
    Route::get('/dashboard/employee', function () {
        return view('dashboard');
    })->middleware('role:Karyawan')->name('dashboard.employee');
    Route::get('/dashboard/warehouse', function () {
        return view('dashboard');
    })->middleware('role:Staf Gudang')->name('dashboard.warehouse');
    Route::get('/dashboard/admin', function () {
        return view('dashboard');
    })->middleware('role:Super Admin')->name('dashboard.admin');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->prefix('hrd')->name('hrd.')->group(function ()  {
    Route::resource('jadwal-shift', JadwalShiftController::class)->except(['show']);
    Route::get('/jadwalkerja', [JadwalKerjaController::class, 'index'])->name('jadwalkerja');
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
require __DIR__ . '/auth.php';
