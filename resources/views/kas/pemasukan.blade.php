@extends('layouts.kas')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-hotel-dark mb-6 uppercase">Input Pemasukan</h1>
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-stone-200">
        <form action="{{ route('kas.store') }}" method="POST">
            @csrf <input type="hidden" name="tipe_transaksi" value="Pemasukan">
            
            <div class="space-y-4">
                <label>Kategori</label>
                <select name="kategori" class="w-full p-3 bg-stone-50 rounded-xl border-stone-200">
                    <option value="Pembayaran Kamar">Pembayaran Kamar</option>
                    <option value="POS Restoran">Pendapatan Restoran</option>
                </select>
                
                <label>Nominal</label>
                <input type="number" name="nominal" class="w-full p-3 bg-stone-50 rounded-xl border-stone-200" required>
                
                <label>Tanggal</label>
                <input type="date" name="tanggal_transaksi" class="w-full p-3 bg-stone-50 rounded-xl border-stone-200" required>
                
                <label>Keterangan</label>
                <textarea name="keterangan" class="w-full p-3 bg-stone-50 rounded-xl border-stone-200"></textarea>
                
                <button class="w-full py-4 bg-hotel-dark text-hotel-gold font-bold rounded-xl">Simpan Pemasukan</button>
            </div>
        </form>
    </div>
</div>
@endsection