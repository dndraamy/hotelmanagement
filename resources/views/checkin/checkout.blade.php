@extends('layouts.reservasi')

@section('content')

<div>
    <h1 class="text-2xl font-bold text-hotel-text">Proses Check-Out</h1>
    <p class="text-sm text-stone-500 mt-1">Kalkulasi tagihan dan konfirmasi check-out tamu.</p>
</div>

@if(session('error'))
    <div class="bg-red-50 border border-red-300 text-red-800 p-4 rounded-xl text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- KIRI: Info Tamu & Waktu --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Data Tamu --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-hotel-gold"></i>
                <h3 class="font-bold text-hotel-text text-sm">Data Tamu</h3>
            </div>
            <div class="px-5 py-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-400">Nama</span>
                    <span class="font-semibold text-hotel-text">{{ $reservasi->tamu->nama_lengkap }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">{{ $reservasi->tamu->tipe_identitas ?? 'Identitas' }}</span>
                    <span class="font-mono text-hotel-text">{{ $reservasi->tamu->no_identitas ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Kontak</span>
                    <span class="text-hotel-text">{{ $reservasi->tamu->kontak ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Detail Reservasi --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-hotel-gold"></i>
                <h3 class="font-bold text-hotel-text text-sm">Detail Reservasi</h3>
            </div>
            <div class="px-5 py-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-400">No. Reservasi</span>
                    <span class="font-mono text-hotel-text">#{{ $reservasi->id_reservasi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Check-in</span>
                    <span class="text-hotel-text">
                        {{ Carbon\Carbon::parse($reservasi->tgl_checkin)->translatedFormat('d M Y') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Check-out (rencana)</span>
                    <span class="text-hotel-text">
                        {{ Carbon\Carbon::parse($reservasi->tgl_checkout)->translatedFormat('d M Y') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Lama Menginap</span>
                    <span class="font-semibold text-hotel-text">{{ $kalkulasi['jumlah_malam'] }} malam</span>
                </div>
                @foreach($reservasi->detailKamar as $detail)
                <div class="flex justify-between">
                    <span class="text-stone-400">Kamar</span>
                    <span class="text-hotel-text">
                        {{ $detail->kamar->nomor_kamar }}
                        <span class="text-stone-400 text-xs">
                            ({{ $detail->kamar->tipeKamar->nama_tipe ?? '-' }})
                        </span>
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Status Waktu --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center gap-2">
                <i data-lucide="clock" class="w-4 h-4 text-hotel-gold"></i>
                <h3 class="font-bold text-hotel-text text-sm">Status Waktu</h3>
            </div>
            <div class="px-5 py-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-400">Batas Check-out</span>
                    <span class="font-semibold text-hotel-text">
                        {{ $kalkulasi['batas_checkout']->translatedFormat('d M Y') }}, 12:00
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-400">Waktu Sekarang</span>
                    <span class="text-hotel-text">
                        {{ $kalkulasi['sekarang']->translatedFormat('d M Y, H:i') }}
                    </span>
                </div>
                @if($kalkulasi['jam_terlambat'] > 0)
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500 shrink-0"></i>
                        <p class="text-xs text-red-700 font-medium">
                            Tamu terlambat <strong>{{ $kalkulasi['jam_terlambat'] }} jam</strong>
                            dari batas check-out.
                        </p>
                    </div>
                @else
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <p class="text-xs text-emerald-700 font-medium">Tepat waktu, tidak ada biaya tambahan.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- KANAN: Rincian Tagihan & Konfirmasi --}}
    <div class="lg:col-span-3 space-y-4">

        {{-- Rincian Tagihan --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center gap-2">
                <i data-lucide="receipt" class="w-4 h-4 text-hotel-gold"></i>
                <h3 class="font-bold text-hotel-text text-sm">Rincian Tagihan</h3>
            </div>
            <div class="px-5 py-5 space-y-4 text-sm">

                {{-- Biaya Kamar --}}
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-hotel-text">Biaya Kamar</p>
                        <p class="text-xs text-stone-400 mt-0.5">
                            Rp {{ number_format($kalkulasi['harga_malam'], 0, ',', '.') }}
                            × {{ $kalkulasi['jumlah_malam'] }} malam
                        </p>
                    </div>
                    <span class="font-semibold text-hotel-text">
                        Rp {{ number_format($kalkulasi['biaya_kamar'], 0, ',', '.') }}
                    </span>
                </div>

                {{-- Biaya Charge --}}
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium {{ $kalkulasi['biaya_charge'] > 0 ? 'text-red-600' : 'text-stone-400' }}">
                            Biaya Keterlambatan
                        </p>
                        @if($kalkulasi['jam_terlambat'] > 0)
                            <p class="text-xs text-red-400 mt-0.5">
                                {{ $kalkulasi['jam_terlambat'] }} jam × 10% harga/malam
                            </p>
                        @else
                            <p class="text-xs text-stone-400 mt-0.5">Tidak ada keterlambatan</p>
                        @endif
                    </div>
                    <span class="font-semibold {{ $kalkulasi['biaya_charge'] > 0 ? 'text-red-600' : 'text-stone-400' }}">
                        Rp {{ number_format($kalkulasi['biaya_charge'], 0, ',', '.') }}
                    </span>
                </div>

                {{-- Total --}}
                <div class="border-t border-dashed border-stone-200 pt-4 flex justify-between items-center">
                    <span class="font-bold text-hotel-text">Total Tagihan</span>
                    <span class="font-bold text-hotel-gold text-xl">
                        Rp {{ number_format($kalkulasi['total_tagihan'], 0, ',', '.') }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Konfirmasi --}}
        <div class="bg-white rounded-2xl border border-stone-200 p-5">
            <p class="text-sm text-stone-500 mb-4">
                Pastikan semua data sudah benar sebelum memproses check-out.
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <form action="{{ route('checkin.prosesCheckout', $reservasi->id_reservasi) }}" method="POST">
                @csrf
                @php
                    $namaTamu     = addslashes($reservasi->tamu->nama_lengkap);
                    $totalTagihan = number_format($kalkulasi['total_tagihan'], 0, ',', '.');
                @endphp
                <div class="flex gap-3">
                    <button type="submit"
                            onclick="return confirm('Konfirmasi check-out {{ $namaTamu }}?\nTotal: Rp {{ $totalTagihan }}')"
                            class="flex-1 flex items-center justify-center gap-2 bg-hotel-dark hover:bg-stone-800 text-white text-sm font-bold px-5 py-3 rounded-xl transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Konfirmasi Check-Out
                    </button>
                    <a href="{{ route('checkin.index') }}"
                       class="flex items-center gap-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-sm font-medium px-5 py-3 rounded-xl transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection