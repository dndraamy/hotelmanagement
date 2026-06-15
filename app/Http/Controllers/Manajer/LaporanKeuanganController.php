<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->filled('bulan') ? $request->bulan : now()->month;
        $tahun = $request->filled('tahun') ? $request->tahun : now()->year;

        // Query total pendapatan per kategori bulan ini
        $pendapatan = DB::table('transaksi_kas')
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->where('tipe_transaksi', 'Pemasukan')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy('kategori')
            ->get();

        // Query total pengeluaran per kategori bulan ini
        $pengeluaran = DB::table('transaksi_kas')
            ->select('kategori', DB::raw('SUM(nominal) as total'))
            ->where('tipe_transaksi', 'Pengeluaran')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy('kategori')
            ->get();

        // Grand total
        $totalPendapatan = DB::table('transaksi_kas')
            ->where('tipe_transaksi', 'Pemasukan')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('nominal');

        $totalPengeluaran = DB::table('transaksi_kas')
            ->where('tipe_transaksi', 'Pengeluaran')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('nominal');

        $selisih = $totalPendapatan - $totalPengeluaran;

        // Data per bulan untuk tabel ringkasan tahunan
        $ringkasanTahunan = DB::table('transaksi_kas')
            ->select(
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('SUM(CASE WHEN tipe_transaksi = "Pemasukan" THEN nominal ELSE 0 END) as total_pendapatan'),
                DB::raw('SUM(CASE WHEN tipe_transaksi = "Pengeluaran" THEN nominal ELSE 0 END) as total_pengeluaran'),
                DB::raw('SUM(CASE WHEN tipe_transaksi = "Pemasukan" THEN nominal ELSE 0 END) - SUM(CASE WHEN tipe_transaksi = "Pengeluaran" THEN nominal ELSE 0 END) as selisih')
            )
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy(DB::raw('MONTH(tanggal_transaksi)'))
            ->orderBy(DB::raw('MONTH(tanggal_transaksi)'))
            ->get();

        // Variabel untuk sidebar layout (dibutuhkan app.blade.php)
        $totalWarning = DB::table('barang')
            ->whereColumn('stok_sekarang', '<', 'stok_minimal')
            ->count();

        $stokMenipisList = DB::table('barang')
            ->whereColumn('stok_sekarang', '<', 'stok_minimal')
            ->get();

        return view('manajer.laporan-keuangan.index', compact(
            'pendapatan',
            'pengeluaran',
            'totalPendapatan',
            'totalPengeluaran',
            'selisih',
            'ringkasanTahunan',
            'bulan',
            'tahun',
            'totalWarning',
            'stokMenipisList'
        ));
    }
    public function exportPdf(Request $request)
{
    $bulan = $request->filled('bulan') ? $request->bulan : now()->month;
    $tahun = $request->filled('tahun') ? $request->tahun : now()->year;

    $pendapatan = DB::table('transaksi_kas')
        ->select('kategori', DB::raw('SUM(nominal) as total'))
        ->where('tipe_transaksi', 'Pemasukan')
        ->whereMonth('tanggal_transaksi', $bulan)
        ->whereYear('tanggal_transaksi', $tahun)
        ->groupBy('kategori')
        ->get();

    $pengeluaran = DB::table('transaksi_kas')
        ->select('kategori', DB::raw('SUM(nominal) as total'))
        ->where('tipe_transaksi', 'Pengeluaran')
        ->whereMonth('tanggal_transaksi', $bulan)
        ->whereYear('tanggal_transaksi', $tahun)
        ->groupBy('kategori')
        ->get();
        
    $totalPendapatan = DB::table('transaksi_kas')
        ->where('tipe_transaksi', 'Pemasukan')
        ->whereMonth('tanggal_transaksi', $bulan)
        ->whereYear('tanggal_transaksi', $tahun)
        ->sum('nominal');

    $totalPengeluaran = DB::table('transaksi_kas')
        ->where('tipe_transaksi', 'Pengeluaran')
        ->whereMonth('tanggal_transaksi', $bulan)
        ->whereYear('tanggal_transaksi', $tahun)
        ->sum('nominal');

    $selisih = $totalPendapatan - $totalPengeluaran;

    $pdf = Pdf::loadView(
        'manajer.laporan-keuangan.pdf',
        compact(
            'pendapatan',
            'pengeluaran',
            'totalPendapatan',
            'totalPengeluaran',
            'selisih',
            'bulan',
            'tahun'
        )
    );

    return $pdf->download(
        'laporan-keuangan-' . $bulan . '-' . $tahun . '.pdf'
    );
}
}
