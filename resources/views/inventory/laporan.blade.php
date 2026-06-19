@extends('layouts.inventory')

@section('content')

<div class="flex items-center justify-between border-b border-stone-200 pb-4 mb-6">

    <div>
        <h1 class="text-2xl font-bold text-hotel-dark">
            Laporan Mutasi Stok
        </h1>

        <p class="text-sm text-stone-500 mt-1">
            Riwayat seluruh aktivitas barang masuk dan barang keluar gudang hotel.
        </p>
    </div>

</div>

{{-- STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm p-5">

        <p class="text-xs uppercase tracking-wider text-stone-500 font-semibold">
            Total Transaksi
        </p>

        <h2 class="text-3xl font-bold text-hotel-dark mt-2">
            {{ $totalMutasi }}
        </h2>

    </div>

    <div class="bg-white border border-emerald-100 rounded-2xl shadow-sm p-5">

        <p class="text-xs uppercase tracking-wider text-emerald-600 font-semibold">
            Barang Masuk
        </p>

        <h2 class="text-3xl font-bold text-emerald-700 mt-2">
            {{ $totalMasuk }}
        </h2>

    </div>

    <div class="bg-white border border-rose-100 rounded-2xl shadow-sm p-5">

        <p class="text-xs uppercase tracking-wider text-rose-600 font-semibold">
            Barang Keluar
        </p>

        <h2 class="text-3xl font-bold text-rose-700 mt-2">
            {{ $totalKeluar }}
        </h2>

    </div>

</div>

{{-- TABEL --}}
<div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">

    <div class="p-6 border-b border-stone-200">

        <h3 class="font-bold text-lg text-hotel-dark">
            Riwayat Mutasi Persediaan
        </h3>

        <p class="text-sm text-stone-500 mt-1">
            Semua transaksi masuk dan keluar tersimpan otomatis.
        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-stone-50">

                <tr class="text-left text-xs uppercase tracking-wider text-stone-500">

                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Barang</th>
                    <th class="px-6 py-4">Supplier</th>
                    <th class="px-6 py-4">Jenis</th>
                    <th class="px-6 py-4">Jumlah</th>
                    <th class="px-6 py-4">Keterangan</th>

                </tr>

            </thead>

            <tbody>

                @forelse($mutasi as $item)

                    <tr class="border-t border-stone-100 hover:bg-stone-50">

                        <td class="px-6 py-4 text-sm text-stone-600">

                            {{ \Carbon\Carbon::parse($item->tanggal_mutasi)->format('d M Y H:i') }}

                        </td>

                        <td class="px-6 py-4 font-semibold text-hotel-dark">

                            {{ $item->barang->nama_barang ?? '-' }}

                        </td>

                        <td class="px-6 py-4 text-sm text-stone-600">

                            {{ $item->supplier->nama_supplier ?? '-' }}

                        </td>

                        <td class="px-6 py-4">

                        @if($item->jenis_mutasi == 'Masuk')

    <span
        class="inline-flex items-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">

        🟢 Barang Masuk

    </span>

@else

    <span
        class="inline-flex items-center whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold bg-rose-100 text-rose-700">

        🔴 Barang Keluar

    </span>

@endif

                        </td>

                        <td class="px-6 py-4 font-bold">

                            {{ $item->jumlah }}

                        </td>

                        <td class="px-6 py-4 text-sm text-stone-600">

                            {{ $item->keterangan }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="px-6 py-10 text-center text-stone-400">

                            Belum ada data mutasi stok.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection