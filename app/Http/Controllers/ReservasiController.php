<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
  public function index()
{
    $reservasi = DB::table('reservasi')
        ->join('tamu', 'reservasi.id_tamu', '=', 'tamu.id_tamu')
        ->select(
            'reservasi.id_reservasi',
            'tamu.nama_lengkap',
            'reservasi.tgl_checkin',
            'reservasi.tgl_checkout',
            'reservasi.status_reservasi'
        )
        ->orderBy('reservasi.created_at', 'desc')
        ->get();

    $totalReservasi     = DB::table('reservasi')->count();
    $confirmedReservasi = DB::table('reservasi')->where('status_reservasi', 'confirmed')->count();
    $pendingReservasi   = DB::table('reservasi')->where('status_reservasi', 'pending')->count();
    $cancelledReservasi = DB::table('reservasi')->where('status_reservasi', 'cancelled')->count();

    return view('reservasi.index', compact(
        'reservasi',
        'totalReservasi',
        'confirmedReservasi',
        'pendingReservasi',
        'cancelledReservasi'
    ));
}
public function create()
{
    return view('reservasi.create');
}

public function store(Request $request)
{
    // Akan diimplementasi di PBI-29
    return redirect()->route('reservasi.index');
}

public function show($id)
{
    $reservasi = DB::table('reservasi')
        ->join('tamu', 'reservasi.id_tamu', '=', 'tamu.id_tamu')
        ->select(
            'reservasi.*',
            'tamu.nama_lengkap',
            'tamu.no_identitas',
            'tamu.kontak'
        )
        ->where('reservasi.id_reservasi', $id)
        ->first();

    return view('reservasi.show', compact('reservasi'));
}

public function edit($id)
{
    // Akan diimplementasi di PBI-29
    return redirect()->route('reservasi.index');
}

public function update(Request $request, $id)
{
    // Akan diimplementasi di PBI-29
    return redirect()->route('reservasi.index');
}

public function destroy($id)
{
    // Akan diimplementasi di PBI-29
    return redirect()->route('reservasi.index');
}
}