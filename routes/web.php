<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\Pegawai\KehadiranController as PegawaiKehadiranController;
use App\Http\Controllers\HRD\KehadiranController as HRDKehadiranController;
use App\Http\Controllers\HRD\ApprovalCutiController;
use App\Http\Controllers\Pegawai\PengajuanCutiController;
use App\Http\Controllers\HRD\PenggajianController;
use App\Http\Controllers\HRD\JadwalShiftController;
use App\Http\Controllers\HRD\JadwalKerjaController;
use App\Http\Controllers\PosRestoranController;
use App\Http\Controllers\RoomChargeController;
use App\Http\Controllers\TransaksiKasController;
use App\Http\Controllers\PenggabunganTagihanController;
use App\Http\Controllers\Manajer\LaporanKeuanganController;
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

// Middleware Auth
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/dashboard/manager', fn () => view('dashboard'))->middleware('role:Manajer Hotel')->name('dashboard.manager');
    Route::get('/dashboard/receptionist', fn () => view('dashboard'))->middleware('role:Resepsionis')->name('dashboard.receptionist');
    Route::get('/dashboard/finance', fn () => view('dashboard'))->middleware('role:Staf Keuangan')->name('dashboard.finance');
    Route::get('/dashboard/restaurant', fn () => view('dashboard'))->middleware('role:Petugas Restoran')->name('dashboard.restaurant');
    Route::get('/dashboard/cleaning', fn () => view('dashboard'))->middleware('role:Petugas Kebersihan')->name('dashboard.cleaning');
    Route::get('/dashboard/employee', fn () => view('dashboard'))->middleware('role:Karyawan')->name('dashboard.employee');
    Route::get('/dashboard/warehouse', fn () => view('dashboard'))->middleware('role:Staf Gudang')->name('dashboard.warehouse');
    Route::get('/dashboard/admin', fn () => view('dashboard'))->middleware('role:Super Admin')->name('dashboard.admin');
    Route::get('/dashboard/hrd', fn () => view('dashboard.hrd.index'))->middleware('role:Staf HRD')->name('dashboard.hrd');
});

Route::middleware(['auth'])->group(function () {
    // Kas & Tagihan
    Route::get('/kas/pemasukan', [TransaksiKasController::class, 'pemasukan'])->name('kas.pemasukan');
    Route::get('/kas/pengeluaran', [TransaksiKasController::class, 'pengeluaran'])->name('kas.pengeluaran');
    Route::post('/kas/store', [TransaksiKasController::class, 'store'])->name('kas.store');
    Route::get('/kas/penggabungan-tagihan', [PenggabunganTagihanController::class, 'index'])->name('penggabungan-tagihan.index');
    Route::post('/kas/penggabungan-tagihan/merge', [PenggabunganTagihanController::class, 'merge'])->name('penggabungan-tagihan.merge');
    Route::post('/kas/penggabungan-tagihan/unmerge', [PenggabunganTagihanController::class, 'unmerge'])->name('penggabungan-tagihan.unmerge');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // POS & Room Charge
    Route::prefix('pos-restoran')->name('pos-restoran.')->group(function () {
        Route::get('/', [PosRestoranController::class, 'index'])->name('index');
        Route::get('/api/tamu-checkedin', [PosRestoranController::class, 'getCheckedInGuests'])->name('tamu-checkedin');
        Route::post('/buat-pesanan', [PosRestoranController::class, 'buatPesanan'])->name('buat-pesanan');
        Route::patch('/{id_pesanan}/charge-to-room', [PosRestoranController::class, 'chargeToRoom'])->name('charge-to-room');
        Route::get('/{id_pesanan}/cetak-dapur', [PosRestoranController::class, 'cetakStrukDapur'])->name('cetak-dapur');
    });
    Route::prefix('room-charge')->name('room-charge.')->group(function () {
        Route::get('/', [RoomChargeController::class, 'index'])->name('index');
        Route::post('/store', [RoomChargeController::class, 'store'])->name('store');
    });

    // Reservasi & Check-in
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/cari/kamar', [ReservasiController::class, 'cariKamar'])->name('reservasi.cari-kamar');
    Route::get('/reservasi/buat/baru', [ReservasiController::class, 'buatReservasi'])->name('reservasi.buat');
    Route::post('/reservasi/simpan', [ReservasiController::class, 'simpanReservasi'])->name('reservasi.simpan-reservasi');
    Route::get('/reservasi/{id}', [ReservasiController::class, 'show'])->name('reservasi.show');
    Route::get('/reservasi/{id}/edit', [ReservasiController::class, 'edit'])->name('reservasi.edit');
    Route::put('/reservasi/{id}', [ReservasiController::class, 'update'])->name('reservasi.update');
    Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
    Route::get('/reservasi/{id}/pembayaran-dp', [ReservasiController::class, 'formPembayaranDP'])->name('reservasi.pembayaran-dp');
    Route::post('/reservasi/{id}/simpan-dp', [ReservasiController::class, 'simpanPembayaranDP'])->name('reservasi.simpan-dp');
    Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
    Route::get('/checkin/{id}', [CheckInController::class, 'show'])->name('checkin.show');
    Route::post('/checkin/{id}/proses', [CheckInController::class, 'proses'])->name('checkin.proses');
    Route::get('/checkin/{id}/checkout', [CheckInController::class, 'showCheckout'])->name('checkin.checkout');
    Route::post('/checkin/{id}/checkout/proses', [CheckInController::class, 'prosesCheckout'])->name('checkin.prosesCheckout');
    Route::get('/checkin/{id}/struk', [CheckInController::class, 'struk'])->name('checkin.struk');

    // Inventory & Housekeeping
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/mutasi', [InventoryController::class, 'mutasi'])->name('inventory.mutasi');
    Route::post('/inventory/masuk', [InventoryController::class, 'barangMasuk'])->name('inventory.masuk');
    Route::post('/inventory/keluar', [InventoryController::class, 'barangKeluar'])->name('inventory.keluar');
    Route::get('/inventory/laporan', [InventoryController::class, 'laporan'])->name('inventory.laporan');
    Route::get('/housekeeping', [HousekeepingController::class, 'index'])->name('housekeeping.index');
    Route::post('/housekeeping/{id}/bersih', [HousekeepingController::class, 'tandaiBersih'])->name('housekeeping.bersih');

    // Pegawai & Kehadiran
    Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
        Route::get('/', [PegawaiKehadiranController::class, 'index'])->name('index');
        Route::post('/checkin', [PegawaiKehadiranController::class, 'checkIn'])->name('checkin');
        Route::post('/checkout', [PegawaiKehadiranController::class, 'checkOut'])->name('checkout');
    });
    Route::get('/pegawai/form_pengajuan', [PengajuanCutiController::class, 'create'])->name('pegawai.cuti.create');
    Route::post('/pegawai/form_pengajuan', [PengajuanCutiController::class, 'store'])->name('pegawai.cuti.store');
    
    // CRUD Pegawai (dari branch HEAD)
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('create');
        Route::post('/', [PegawaiController::class, 'store'])->name('store');
        Route::get('/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('edit');
        Route::put('/{pegawai}', [PegawaiController::class, 'update'])->name('update');
        Route::delete('/{pegawai}', [PegawaiController::class, 'destroy'])->name('destroy');
    });
});

// HRD Routes
Route::middleware(['auth'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::resource('jadwal-shift', JadwalShiftController::class)->except(['show']);
    Route::get('/jadwalkerja', [JadwalKerjaController::class, 'index'])->name('jadwalkerja');
    
    Route::prefix('dashboard/hrd')->name('dashboard.hrd.')->group(function () {
        Route::get('/kehadiran', [HRDKehadiranController::class, 'index'])->name('kehadiran.index');
        Route::get('/cuti', [ApprovalCutiController::class, 'index'])->name('cuti.index');
        Route::get('/cuti/{pengajuanCuti}', [ApprovalCutiController::class, 'show'])->name('cuti.show');
        Route::patch('/cuti/{pengajuanCuti}/approve', [ApprovalCutiController::class, 'approve'])->name('cuti.approve');
        Route::patch('/cuti/{pengajuanCuti}/reject', [ApprovalCutiController::class, 'reject'])->name('cuti.reject');
        Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
        Route::post('/penggajian/generate', [PenggajianController::class, 'generate'])->name('penggajian.generate');
        Route::get('/penggajian/{id}/cetak', [PenggajianController::class, 'cetakSlip'])->name('penggajian.cetak');
    });
});

// Manajer Routes
Route::middleware(['auth', 'role:Manajer Hotel'])->prefix('manajer')->name('manajer.')->group(function () {
    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
    Route::get('/laporan-keuangan/export-pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('laporan-keuangan.export-pdf');
});

require __DIR__.'/auth.php';