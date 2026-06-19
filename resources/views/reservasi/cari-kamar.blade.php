@extends('layouts.reservasi')

@section('content')

<div>
    <h1 class="text-2xl font-bold text-hotel-text">Pencarian Kamar</h1>
    <p class="text-sm text-stone-500 mt-1">Cari kamar yang tersedia berdasarkan tanggal menginap dan tipe kamar.</p>
</div>

{{-- FORM PENCARIAN --}}
<div class="bg-white rounded-2xl border border-stone-200 p-6">
    <form action="{{ route('reservasi.cari-kamar') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Check-in</label>
            <input type="date" name="tgl_checkin" value="{{ request('tgl_checkin') }}" required
                class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
        </div>
        <div>
            <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Check-out</label>
            <input type="date" name="tgl_checkout" value="{{ request('tgl_checkout') }}" required
                class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
        </div>
        <div>
            <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Tipe Kamar</label>
            <select name="id_tipe" class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                <option value="">Semua Tipe</option>
                @foreach($tipeKamar as $tipe)
                <option value="{{ $tipe->id_tipe }}" {{ request('id_tipe') == $tipe->id_tipe ? 'selected' : '' }}>
                    {{ $tipe->nama_tipe }} — Rp {{ number_format($tipe->harga_per_malam, 0, ',', '.') }}/malam
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="flex items-center justify-center gap-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold px-4 py-2 rounded-xl transition h-[42px]">
            <i data-lucide="search" class="w-4 h-4"></i>
            Cari Kamar
        </button>
    </form>

    @error('tgl_checkin') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
    @error('tgl_checkout') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
</div>

{{-- HASIL PENCARIAN --}}
@if($sudahCari)
<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-stone-100">
        <h3 class="font-bold text-hotel-text">Hasil Pencarian</h3>
        <p class="text-xs text-stone-400 mt-0.5">{{ $kamarTersedia->count() }} kamar tersedia untuk tanggal yang dipilih</p>
    </div>

    @if($kamarTersedia->isEmpty())
    <div class="px-5 py-12 text-center text-stone-400">
        <i data-lucide="bed-double" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
        <p>Tidak ada kamar tersedia untuk tanggal & tipe yang dipilih</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
        @foreach($kamarTersedia as $kamar)
        <div class="border border-stone-200 rounded-2xl p-5 hover:shadow-md transition">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-[11px] uppercase tracking-widest text-stone-400 font-semibold">Kamar</p>
                    <p class="text-xl font-bold text-hotel-text">{{ $kamar->nomor_kamar }}</p>
                    <p class="text-sm text-stone-500">{{ $kamar->tipeKamar->nama_tipe ?? 'Tipe Kamar' }}</p>
                </div>
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Tersedia</span>
            </div>

            <div class="border-t border-stone-100 pt-3 mt-2 flex justify-between items-center">
                <div>
                    <p class="text-xs text-stone-400">Harga per malam</p>
                    <p class="text-lg font-bold text-hotel-gold">Rp {{ number_format($kamar->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('reservasi.buat') }}?id_kamar={{ $kamar->id_kamar }}&tgl_checkin={{ request('tgl_checkin') }}&tgl_checkout={{ request('tgl_checkout') }}"
                    class="bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold px-4 py-2 rounded-xl transition">
                    Pilih
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif

@endsection