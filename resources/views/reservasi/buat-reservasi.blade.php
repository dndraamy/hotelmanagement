@extends('layouts.reservasi')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-hotel-text">Buat Reservasi Baru</h1>
    <p class="text-sm text-stone-500 mt-1">Isi data tamu dan detail reservasi</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- FORM RESERVASI --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 p-6">
        <form action="{{ route('reservasi.simpan-reservasi') }}" method="POST">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" name="id_kamar" value="{{ $kamar->id_kamar }}">
            <input type="hidden" name="tgl_checkin" value="{{ $tgl_checkin }}">
            <input type="hidden" name="tgl_checkout" value="{{ $tgl_checkout }}">

            {{-- Informasi Kamar --}}
            <div class="bg-hotel-gold/10 rounded-xl p-4 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-stone-500 uppercase tracking-wider">Kamar Dipilih</p>
                        <p class="text-xl font-bold text-hotel-text">{{ $kamar->nomor_kamar }}</p>
                        <p class="text-sm text-stone-600">{{ $kamar->tipeKamar->nama_tipe }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-stone-500 uppercase tracking-wider">Total Harga</p>
                        <p class="text-2xl font-bold text-hotel-gold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                        <p class="text-xs text-stone-400">{{ $jumlahMalam }} malam x Rp {{ number_format($kamar->tipeKamar->harga_per_malam, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Data Tamu --}}
            <h3 class="font-semibold text-hotel-text mb-4">Data Tamu</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Tipe Identitas</label>
                    <select name="tipe_identitas" required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                        <option value="KTP">KTP</option>
                        <option value="SIM">SIM</option>
                        <option value="Paspor">Paspor</option>
                        <option value="Kartu Pelajar">Kartu Pelajar</option>
                    </select>
                    @error('tipe_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Nomor Identitas</label>
                    <input type="text" name="no_identitas" value="{{ old('no_identitas') }}" required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @error('no_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Kontak (No. HP/Email)</label>
                    <input type="text" name="kontak" value="{{ old('kontak') }}" required
                        class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @error('kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('reservasi.cari-kamar') }}"
                    class="px-4 py-2 border border-stone-300 rounded-xl text-sm hover:bg-stone-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold rounded-xl transition">
                    <i data-lucide="save" class="w-4 h-4 inline mr-1"></i>
                    Simpan Reservasi
                </button>
            </div>
        </form>
    </div>

    {{-- SIDEBAR INFO --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-stone-200 p-6 sticky top-4">
            <h4 class="font-semibold text-hotel-text mb-4">Ringkasan</h4>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-500">Check-in</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($tgl_checkin)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Check-out</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($tgl_checkout)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Lama Menginap</span>
                    <span class="font-medium">{{ $jumlahMalam }} malam</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Tipe Kamar</span>
                    <span class="font-medium">{{ $kamar->tipeKamar->nama_tipe }}</span>
                </div>
                <div class="border-t border-stone-200 pt-3 mt-3">
                    <div class="flex justify-between text-base font-bold">
                        <span>Total Tagihan</span>
                        <span class="text-hotel-gold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-stone-400 mt-1 text-right">*DP 50% akan diminta saat check-in</p>
                </div>
            </div>

            <div class="mt-4 p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                <p class="text-xs text-yellow-700">
                    <i data-lucide="info" class="w-3 h-3 inline"></i>
                    Reservasi akan otomatis dibuat dengan status <strong>"Menunggu DP"</strong>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection