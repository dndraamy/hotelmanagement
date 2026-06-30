<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;

class HousekeepingController extends Controller
{
    public function index()
    {
        $kamarKotor = Kamar::where('status_kamar', 'Kotor')->get();

        return view('housekeeping.index', compact('kamarKotor'));
    }

     public function tandaiBersih($id)
    {
    $kamar = Kamar::findOrFail($id);

    $kamar->status_kamar = 'Bersih';
    $kamar->save();

    return redirect()
        ->route('housekeeping.index')
        ->with('success', 'Status kamar berhasil diperbarui.');
    }
}
