<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\MutasiStok;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryController extends Controller
{
    // Dashboard stok
public function index(Request $request)
{
    $search = $request->search;

    $barang = Barang::query()
        ->when($search, function ($query) use ($search) {
            $query->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
        })
        ->get();

    $totalItem = $barang->count();

    $supplierCount = Supplier::count();

    $stokRendahCount = Barang::whereColumn(
        'stok_sekarang',
        '<=',
        'stok_minimal'
    )->count();

    /*
    |--------------------------------------------------------------------------
    | DATA CHART
    |--------------------------------------------------------------------------
    */

    $chartMasuk = [];
    $chartKeluar = [];

    for ($i = 1; $i <= 12; $i++) {

        $chartMasuk[] = MutasiStok::whereMonth(
            'tanggal_mutasi',
            $i
        )
        ->where('jenis_mutasi', 'Masuk')
        ->sum('jumlah');

        $chartKeluar[] = MutasiStok::whereMonth(
            'tanggal_mutasi',
            $i
        )
        ->where('jenis_mutasi', 'Keluar')
        ->sum('jumlah');
    }

    return view('inventory.dashboard', compact(
        'barang',
        'totalItem',
        'supplierCount',
        'stokRendahCount',
        'search',
        'chartMasuk',
        'chartKeluar'
    ));
}


    // Halaman mutasi
    public function mutasi()
    {
        $barang = Barang::orderBy('nama_barang')->get();

        $supplier = Supplier::orderBy('nama_supplier')->get();

        return view('inventory.mutasi', compact(
            'barang',
            'supplier'
        ));
    }

    // Barang masuk
    public function barangMasuk(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'id_supplier' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {

            MutasiStok::create([
                'id_barang' => $request->id_barang,
                'id_supplier' => $request->id_supplier,
                'jenis_mutasi' => 'Masuk',
                'jumlah' => $request->jumlah,
                'tanggal_mutasi' => now(),
                'keterangan' => $request->keterangan,
            ]);

            $barang = Barang::findOrFail($request->id_barang);

            $barang->increment(
                'stok_sekarang',
                $request->jumlah
            );
        });

        return redirect()
            ->route('inventory.mutasi')
            ->with('success', 'Barang masuk berhasil dicatat');
    }

    // Barang keluar
    public function barangKeluar(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail(
            $request->id_barang
        );

        if ($barang->stok_sekarang < $request->jumlah) {

            return back()->with(
                'error',
                'Stok tidak mencukupi'
            );
        }

        DB::transaction(function () use (
            $request,
            $barang
        ) {

            MutasiStok::create([
                'id_barang' => $request->id_barang,
                'id_supplier' => null,
                'jenis_mutasi' => 'Keluar',
                'jumlah' => $request->jumlah,
                'tanggal_mutasi' => now(),
                'keterangan' => $request->keterangan,
            ]);

            $barang->decrement(
                'stok_sekarang',
                $request->jumlah
            );
        });

        return redirect()
            ->route('inventory.mutasi')
            ->with('success', 'Barang keluar berhasil dicatat');
    }

    // Laporan
public function laporan()
{
    $mutasi = MutasiStok::with([
        'barang',
        'supplier'
    ])
    ->orderByDesc('tanggal_mutasi')
    ->get();

    $totalMutasi = $mutasi->count();

    $totalMasuk = $mutasi
        ->where('jenis_mutasi', 'Masuk')
        ->sum('jumlah');

    $totalKeluar = $mutasi
        ->where('jenis_mutasi', 'Keluar')
        ->sum('jumlah');

    return view('inventory.laporan', compact(
        'mutasi',
        'totalMutasi',
        'totalMasuk',
        'totalKeluar'
    ));
}
}