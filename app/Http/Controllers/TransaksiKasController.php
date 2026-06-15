<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKas;
use Illuminate\Http\Request;

class TransaksiKasController extends Controller
{
    public function pemasukan() { return view('kas.pemasukan'); }
    public function pengeluaran() { return view('kas.pengeluaran'); }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_transaksi' => 'required',
            'kategori' => 'required',
            'nominal' => 'required|numeric',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required',
            'bukti_nota' => 'nullable|image|max:2048',
        ]);

        $pathNota = null;
        if ($request->hasFile('bukti_nota')) {
            $pathNota = $request->file('bukti_nota')->store('nota_transaksi', 'public');
        }

        TransaksiKas::create([
            'id_user' => auth()->id(),
            'tipe_transaksi' => $request->tipe_transaksi,
            'kategori' => $request->kategori,
            'nominal' => $request->nominal,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'keterangan' => $request->keterangan,
            'bukti_nota_url' => $pathNota,
        ]);

        return back()->with('success', 'Data ' . $request->tipe_transaksi . ' berhasil disimpan!');
    }
}