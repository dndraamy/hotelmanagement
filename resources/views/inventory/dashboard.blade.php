@extends('layouts.inventory')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between border-b border-stone-200 pb-4">

    <div>
        <h1 class="text-2xl font-bold text-hotel-dark">
            Dashboard & Manajemen Stok
        </h1>

        <p class="text-sm text-stone-500 mt-1">
            Monitor status ketersediaan barang hotel secara real-time.
        </p>
    </div>

</div>

<!-- CARD STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Total Barang -->
    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm p-6 flex items-center justify-between">

        <div>

            <p class="text-xs uppercase tracking-widest text-stone-500 font-semibold">
                Total SKU Barang
            </p>

            <h2 class="text-4xl font-bold text-hotel-dark mt-2">
                {{ $totalItem }}
            </h2>

            <p class="text-xs text-stone-500 mt-2">
                Item Terdaftar
            </p>

        </div>

        <div class="w-16 h-16 rounded-2xl bg-stone-100 flex items-center justify-center">
            <i data-lucide="package" class="w-8 h-8 text-stone-600"></i>
        </div>

    </div>

    <!-- Warning -->
    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm p-6 flex items-center justify-between">

        <div>

            <p class="text-xs uppercase tracking-widest text-stone-500 font-semibold">
                Perlu Restock
            </p>

            <h2 class="text-4xl font-bold {{ $stokRendahCount > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-2">
                {{ $stokRendahCount }}
            </h2>

            <p class="text-xs text-stone-500 mt-2">
                Item Di Bawah Minimum
            </p>

        </div>

        <div class="w-16 h-16 rounded-2xl flex items-center justify-center
            {{ $stokRendahCount > 0
                ? 'bg-rose-50 text-rose-600'
                : 'bg-emerald-50 text-emerald-600'
            }}">

            <i data-lucide="alert-triangle" class="w-8 h-8"></i>

        </div>

    </div>

    <!-- Supplier -->
    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm p-6 flex items-center justify-between">

        <div>

            <p class="text-xs uppercase tracking-widest text-stone-500 font-semibold">
                Supplier Aktif
            </p>

            <h2 class="text-4xl font-bold text-hotel-gold mt-2">
                {{ $supplierCount }}
            </h2>

            <p class="text-xs text-stone-500 mt-2">
                Vendor Terintegrasi
            </p>

        </div>

        <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center">

            <i data-lucide="users" class="w-8 h-8 text-hotel-gold"></i>

        </div>

    </div>

</div>
<div class="bg-white border border-stone-200 rounded-2xl shadow-sm p-6 mb-6">

    <div class="flex items-center justify-between mb-5">

        <div>
            <h3 class="text-lg font-bold text-hotel-dark">
                Grafik Pergerakan Stok
            </h3>

            <p class="text-sm text-stone-500">
                Barang masuk dan keluar selama tahun berjalan.
            </p>
        </div>

    </div>

    <canvas id="mutasiChart" height="90"></canvas>

</div>
<!-- TABEL INVENTORY -->
<div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">

    <!-- HEADER TABEL -->
    <div class="p-6 border-b border-stone-200">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h3 class="font-bold text-lg text-hotel-dark">
                    Laporan Persediaan Gudang
                </h3>

                <p class="text-sm text-stone-500 mt-1">
                    Monitoring persediaan barang hotel secara langsung.
                </p>

            </div>

            <!-- SEARCH -->
            <form method="GET"
                  action="{{ route('inventory.index') }}"
                  class="flex gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama barang atau kategori..."
                    class="w-72 border border-stone-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none">

                <button
                    type="submit"
                    class="bg-hotel-dark hover:bg-black text-hotel-gold px-5 py-2 rounded-xl text-sm font-semibold transition">

                    Cari

                </button>

            </form>

        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider text-xs">

                    <th class="p-4 text-left">SKU</th>
                    <th class="p-4 text-left">Nama Barang</th>
                    <th class="p-4 text-left">Kategori</th>
                    <th class="p-4 text-left">Stok</th>
                    <th class="p-4 text-left">Satuan</th>
                    <th class="p-4 text-left">Minimal</th>
                    <th class="p-4 text-left">Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($barang as $b)

                    <tr class="border-b border-stone-100 hover:bg-stone-50 transition">

                        <td class="p-4 font-mono text-xs text-stone-500">
                            SKU-{{ str_pad($b->id_barang, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="p-4 font-semibold text-hotel-dark">
                            {{ $b->nama_barang }}
                        </td>

                        <td class="p-4">

                            <span class="px-3 py-1 rounded-full bg-stone-100 text-stone-600 text-xs">

                                {{ $b->kategori }}

                            </span>

                        </td>

                        <td class="p-4">

                            @if($b->stok_sekarang <= $b->stok_minimal)

                                <span class="font-bold text-rose-600">
                                    {{ $b->stok_sekarang }}
                                </span>

                            @else

                                <span class="font-bold text-stone-800">
                                    {{ $b->stok_sekarang }}
                                </span>

                            @endif

                        </td>

                        <td class="p-4 text-stone-500">
                            {{ $b->satuan }}
                        </td>

                        <td class="p-4 text-stone-500">
                            {{ $b->stok_minimal }}
                        </td>

                        <td class="p-4">

                            @if($b->stok_sekarang <= $b->stok_minimal)

                                <span class="bg-rose-50 text-rose-700 border border-rose-200 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-2">

                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>

                                    Butuh Restock

                                </span>

                            @else

                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-2">

                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                                    Aman

                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center py-10 text-stone-400">

                            Tidak ada data barang ditemukan.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
<script>

document.addEventListener('DOMContentLoaded', function () {

    const ctx = document
        .getElementById('mutasiChart')
        .getContext('2d');

    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des'
            ],

            datasets: [

                {
                    label: 'Barang Masuk',

                    data: @json($chartMasuk),

                    borderWidth: 1
                },

                {
                    label: 'Barang Keluar',

                    data: @json($chartKeluar),

                    borderWidth: 1
                }

            ]
        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    position: 'top'
                }
            },

            scales: {

                y: {
                    beginAtZero: true
                }
            }
        }
    });

});

</script>
@endsection