<?php

use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Pegawai\KehadiranController as PegawaiKehadiranController;
use App\Http\Controllers\HRD\KehadiranController as HRDKehadiranController;
use App\Http\Controllers\HRD\ApprovalCutiController;
use App\Http\Controllers\Pegawai\PengajuanCutiController;
use App\Http\Controllers\HRD\PenggajianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiKasController;
use App\Http\Controllers\PenggabunganTagihanController;
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
    // Route::get('/dashboard/hrd', function () {
    //     return view('dashboard');
    // })->middleware('role:Staf HRD')->name('dashboard.hrd');
    Route::get('/dashboard/employee', function () {
        return view('dashboard');
    })->middleware('role:Karyawan')->name('dashboard.employee');
    Route::get('/dashboard/warehouse', function () {
        return view('dashboard');
    })->middleware('role:Staf Gudang')->name('dashboard.warehouse');
    Route::get('/dashboard/admin', function () {
        return view('dashboard');
    })->middleware('role:Super Admin')->name('dashboard.admin');

    Route::get('/dashboard/hrd', function () {
        return view('dashboard.hrd.index'); 
    })->middleware('role:Staf HRD')->name('dashboard.hrd');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/kas/pemasukan', [TransaksiKasController::class, 'pemasukan'])->name('kas.pemasukan');
    Route::get('/kas/pengeluaran', [TransaksiKasController::class, 'pengeluaran'])->name('kas.pengeluaran');
    Route::post('/kas/store', [TransaksiKasController::class, 'store'])->name('kas.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/housekeeping', [HousekeepingController::class, 'index'])
        ->name('housekeeping.index');
        Route::post('/housekeeping/{id}/bersih', [HousekeepingController::class, 'tandaiBersih'])
    ->name('housekeeping.bersih');
});

require __DIR__.'/auth.php';
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

    Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
        Route::get('/', [PegawaiKehadiranController::class, 'index'])->name('index');
        Route::post('/checkin', [PegawaiKehadiranController::class, 'checkIn'])->name('checkin');
        Route::post('/checkout', [PegawaiKehadiranController::class, 'checkOut'])->name('checkout');
    });

    Route::get('/pegawai/form_pengajuan', [PengajuanCutiController::class, 'create'])->name('pegawai.cuti.create');
    Route::post('/pegawai/form_pengajuan', [PengajuanCutiController::class, 'store'])->name('pegawai.cuti.store');

    Route::prefix('dashboard/hrd')->name('dashboard.hrd.')->group(function () {
        Route::get('/kehadiran', [HRDKehadiranController::class, 'index'])->name('kehadiran.index');
        Route::get('/cuti',                           [ApprovalCutiController::class, 'index'])->name('cuti.index');
        Route::get('/cuti/{pengajuanCuti}',           [ApprovalCutiController::class, 'show'])->name('cuti.show');
        Route::patch('/cuti/{pengajuanCuti}/approve', [ApprovalCutiController::class, 'approve'])->name('cuti.approve');
        Route::patch('/cuti/{pengajuanCuti}/reject',  [ApprovalCutiController::class, 'reject'])->name('cuti.reject');
        Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
        Route::post('/penggajian/generate', [PenggajianController::class, 'generate'])->name('penggajian.generate');
        Route::get('/penggajian/{id}/cetak', [PenggajianController::class, 'cetakSlip'])->name('penggajian.cetak');
    });

    
  // ─── Penggabungan Tagihan ────────────────────────────────────────────────
  Route::get('/kas/penggabungan-tagihan', [PenggabunganTagihanController::class, 'index'])
    ->name('penggabungan-tagihan.index');

Route::post('/kas/penggabungan-tagihan/merge', [PenggabunganTagihanController::class, 'merge'])
    ->name('penggabungan-tagihan.merge');

Route::post('/kas/penggabungan-tagihan/unmerge', [PenggabunganTagihanController::class, 'unmerge'])
    ->name('penggabungan-tagihan.unmerge');
});
require __DIR__.'/auth.php';