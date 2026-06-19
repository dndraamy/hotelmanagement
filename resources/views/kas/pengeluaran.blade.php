@extends('layouts.kas')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-hotel-dark uppercase tracking-wider">Input Pengeluaran</h1>
    </div>

    <div class="bg-white shadow-sm border border-stone-200 rounded-2xl p-8">
        <form action="{{ route('kas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf <input type="hidden" name="tipe_transaksi" value="Pengeluaran">

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-stone-700">Kategori</label>
                    <select name="kategori" class="w-full mt-2 p-3 bg-stone-50 border border-stone-200 rounded-xl" required>
                        <option value="Operasional">Belanja Operasional</option>
                        <option value="Gaji">Pembayaran Gaji</option>
                        <option value="Perbaikan">Maintenance/Perbaikan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-700">Nominal (Rp)</label>
                    <input type="number" name="nominal" class="w-full mt-2 p-3 bg-stone-50 border border-stone-200 rounded-xl" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-700">Tanggal</label>
                    <input type="date" name="tanggal_transaksi" class="w-full mt-2 p-3 bg-stone-50 border border-stone-200 rounded-xl" required>
                </div>
                <div>
                      <label class="block text-sm font-semibold text-stone-700">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full p-3 bg-stone-50 rounded-xl border-stone-200" placeholder="Tambahkan rincian..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-700">Bukti Nota</label>
                    <input type="file" name="bukti_nota" class="w-full mt-2 p-3 bg-stone-50 border border-stone-200 rounded-xl">
                </div>
                <button type="submit" class="w-full py-4 bg-hotel-dark text-hotel-gold font-bold rounded-xl">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
@endsection