@extends('layouts.inventory')

@section('content')

<div class="flex items-center justify-between border-b border-stone-200 pb-4">

<div>
    <h1 class="text-2xl font-bold text-hotel-dark">
        Mutasi Stok Barang
    </h1>

    <p class="text-sm text-stone-500 mt-1">
        Kelola barang masuk dan barang keluar gudang hotel.
    </p>
</div>


</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

<!-- BARANG MASUK -->
<div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">

    <div class="bg-emerald-50 border-b border-emerald-100 p-5">

        <h2 class="font-bold text-emerald-700 text-lg flex items-center gap-2">
            <i data-lucide="arrow-down-circle" class="w-5 h-5"></i>
            Barang Masuk
        </h2>

    </div>

    <form action="{{ route('inventory.masuk') }}" method="POST" class="p-6 space-y-4">

        @csrf

        <div>
            <label class="block text-sm font-medium mb-2">
                Nama Barang
            </label>

            <select name="id_barang"
                class="w-full border border-stone-300 rounded-xl px-4 py-3">

                @foreach($barang as $item)

                    <option value="{{ $item->id_barang }}">
                        {{ $item->nama_barang }}
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Supplier
            </label>

            <select name="id_supplier"
                class="w-full border border-stone-300 rounded-xl px-4 py-3">

                @foreach($supplier as $sup)

                    <option value="{{ $sup->id_supplier }}">
                        {{ $sup->nama_supplier }}
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Jumlah
            </label>

            <input
                type="number"
                min="1"
                name="jumlah"
                class="w-full border border-stone-300 rounded-xl px-4 py-3"
                required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Keterangan
            </label>

            <textarea
                name="keterangan"
                rows="3"
                class="w-full border border-stone-300 rounded-xl px-4 py-3"></textarea>
        </div>

        <button
            type="submit"
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition">

            Simpan Barang Masuk

        </button>

    </form>

</div>

<!-- BARANG KELUAR -->
<div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">

    <div class="bg-rose-50 border-b border-rose-100 p-5">

        <h2 class="font-bold text-rose-700 text-lg flex items-center gap-2">
            <i data-lucide="arrow-up-circle" class="w-5 h-5"></i>
            Barang Keluar
        </h2>

    </div>

    <form action="{{ route('inventory.keluar') }}" method="POST" class="p-6 space-y-4">

        @csrf

        <div>
            <label class="block text-sm font-medium mb-2">
                Nama Barang
            </label>

            <select
                name="id_barang"
                class="w-full border border-stone-300 rounded-xl px-4 py-3">

                @foreach($barang as $item)

                    <option value="{{ $item->id_barang }}">
                        {{ $item->nama_barang }}
                        (Stok: {{ $item->stok_sekarang }})
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Jumlah
            </label>

            <input
                type="number"
                min="1"
                name="jumlah"
                class="w-full border border-stone-300 rounded-xl px-4 py-3"
                required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Keterangan
            </label>

            <textarea
                name="keterangan"
                rows="3"
                class="w-full border border-stone-300 rounded-xl px-4 py-3"></textarea>
        </div>

        <button
            type="submit"
            class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold py-3 rounded-xl transition">

            Simpan Barang Keluar

        </button>

    </form>

</div>


</div>

<!-- INFO -->

<div class="bg-white border border-stone-200 rounded-2xl shadow-sm p-6">

<h3 class="font-bold text-lg mb-4">
    Informasi Mutasi
</h3>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <div class="bg-stone-50 rounded-xl p-4">
        <p class="text-xs text-stone-500 uppercase">
            Total Barang
        </p>

        <p class="text-2xl font-bold mt-2">
            {{ $barang->count() }}
        </p>
    </div>

    <div class="bg-emerald-50 rounded-xl p-4">
        <p class="text-xs text-emerald-600 uppercase">
            Supplier Aktif
        </p>

        <p class="text-2xl font-bold mt-2 text-emerald-700">
            {{ $supplier->count() }}
        </p>
    </div>

    <div class="bg-amber-50 rounded-xl p-4">
        <p class="text-xs text-amber-600 uppercase">
            Monitoring
        </p>

        <p class="text-sm font-semibold mt-2 text-amber-700">
            Semua transaksi tercatat otomatis.
        </p>
    </div>

</div>


</div>

@endsection
