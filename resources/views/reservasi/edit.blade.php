@extends('layouts.reservasi')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-hotel-text">Edit Reservasi</h1>
    <p class="text-sm text-stone-500 mt-1">Ubah data reservasi #{{ $reservasi->id_reservasi }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- FORM EDIT RESERVASI --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 p-6">

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reservasi.update', $reservasi->id_reservasi) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Data Tamu --}}
            <h3 class="font-semibold text-hotel-text mb-4">Data Tamu</h3>
            <div class="grid grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Nama Tamu</label>
                    <select name="id_tamu" required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                        @foreach ($tamu as $t)
                            <option value="{{ $t->id_tamu }}"
                                {{ old('id_tamu', $reservasi->id_tamu) == $t->id_tamu ? 'selected' : '' }}>
                                {{ $t->nama_lengkap }} ({{ $t->no_identitas }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_tamu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Detail Kamar & Tanggal --}}
            <h3 class="font-semibold text-hotel-text mb-4">Detail Kamar &amp; Tanggal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $idKamarSekarang = $reservasi->detailKamar->first()->id_kamar ?? null;
                @endphp
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Kamar</label>
                    <select name="id_kamar" required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                        @foreach ($kamar as $k)
                            <option value="{{ $k->id_kamar }}"
                                {{ old('id_kamar', $idKamarSekarang) == $k->id_kamar ? 'selected' : '' }}>
                                Kamar {{ $k->nomor_kamar }} — {{ $k->tipeKamar->nama_tipe }}
                                (Rp {{ number_format($k->tipeKamar->harga_per_malam, 0, ',', '.') }}/malam)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-stone-400 mt-1">Daftar berisi kamar kosong + kamar yang sedang dipakai reservasi ini.</p>
                    @error('id_kamar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Tanggal Check-in</label>
                    <input type="date" name="tgl_checkin"
                        value="{{ old('tgl_checkin', \Carbon\Carbon::parse($reservasi->tgl_checkin)->format('Y-m-d')) }}"
                        required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @error('tgl_checkin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Tanggal Check-out</label>
                    <input type="date" name="tgl_checkout"
                        value="{{ old('tgl_checkout', \Carbon\Carbon::parse($reservasi->tgl_checkout)->format('Y-m-d')) }}"
                        required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @error('tgl_checkout') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Status Reservasi --}}
            <h3 class="font-semibold text-hotel-text mt-6 mb-4">Status Reservasi</h3>
            <div>
                <select name="status_reservasi" required
                    class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @foreach (['Menunggu DP', 'Confirmed', 'Checked-In', 'Checked-Out', 'Cancelled'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status_reservasi', $reservasi->status_reservasi) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
                @error('status_reservasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('reservasi.index') }}"
                    class="px-4 py-2 border border-stone-300 rounded-xl text-sm hover:bg-stone-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold rounded-xl transition">
                    <i data-lucide="save" class="w-4 h-4 inline mr-1"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- SIDEBAR INFO --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-stone-200 p-6 sticky top-4">
            <h4 class="font-semibold text-hotel-text mb-4">Info Saat Ini</h4>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-500">ID Reservasi</span>
                    <span class="font-medium">#{{ $reservasi->id_reservasi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Biaya Kamar Saat Ini</span>
                    <span class="font-medium">Rp {{ number_format($reservasi->biaya_kamar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Total Tagihan Saat Ini</span>
                    <span class="font-medium">Rp {{ number_format($reservasi->total_tagihan, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4 p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                <p class="text-xs text-yellow-700">
                    <i data-lucide="info" class="w-3 h-3 inline"></i>
                    Biaya kamar &amp; total tagihan akan dihitung ulang otomatis berdasarkan kamar dan tanggal yang dipilih saat disimpan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection