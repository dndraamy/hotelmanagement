<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKas;
use Illuminate\Http\Request;

class TransaksiKasController extends Controller
{
    // Menampilkan halaman form
    public function index()
    {
        return view('finance.transaksi');
    }

    // Menyimpan data ke database
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'tipe_transaksi' => 'required|in:Pemasukan,Pengeluaran',
            'kategori' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string',
            'bukti_nota' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $pathNota = null;

        // Jika transaksi adalah Pengeluaran dan ada file nota yang diunggah
        if ($request->tipe_transaksi === 'Pengeluaran' && $request->hasFile('bukti_nota')) {
            $pathNota = $request->file('bukti_nota')->store('nota_transaksi', 'public');
        }

        // Simpan ke database
        TransaksiKas::create([
            'id_user' => auth()->user()->id_user ?? auth()->id(), // Menyesuaikan dengan struktur PK users
            'tipe_transaksi' => $request->tipe_transaksi,
            'kategori' => $request->kategori,
            'nominal' => $request->nominal,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'keterangan' => $request->keterangan,
            'bukti_nota_url' => $pathNota,
        ]);

        return back()->with('success', 'Data transaksi kas berhasil disimpan!');
    }
}