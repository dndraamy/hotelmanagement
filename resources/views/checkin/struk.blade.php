@extends('layouts.reservasi')

@push('scripts')
<script>
    window.addEventListener('load', function () {
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.print();
        }
    });
</script>
<style>
    @media print {
        aside, header, .no-print { display: none !important; }
        body { background: white !important; }
    }
</style>
@endpush

@section('content')

<div class="no-print">
    <h1 class="text-2xl font-bold text-hotel-text">Struk Pelunasan</h1>
    <p class="text-sm text-stone-500 mt-1">Cetak atau simpan struk pembayaran tamu.</p>
</div>

{{-- Tombol Aksi --}}
<div class="flex gap-3 no-print">
    <button onclick="window.print()"
            class="flex items-center gap-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold px-5 py-2.5 rounded-xl transition">
        <i data-lucide="printer" class="w-4 h-4"></i>
        Cetak Struk
    </button>
    <a href="{{ route('checkin.index') }}"
       class="flex items-center gap-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-sm font-medium px-5 py-2.5 rounded-xl transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>
</div>

{{-- Struk --}}
<div class="max-w-lg mx-auto bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">

    {{-- Header --}}
    <div class="bg-hotel-dark px-8 py-6 text-center">
        <img src="{{ asset('logo_hotel.png') }}" alt="RBPL Hotel"
             class="w-16 h-16 object-contain mx-auto mb-3">
        <h2 class="text-hotel-gold font-bold tracking-[0.2em] text-sm">RBPL HOTEL</h2>
        <p class="text-stone-400 text-xs mt-1">Struk Pelunasan</p>
    </div>

    <div class="px-8 py-6 space-y-5 text-sm">

        {{-- No. Struk --}}
        <div class="flex justify-between text-xs text-stone-400 border-b border-dashed border-stone-200 pb-4">
            <span>No. Reservasi: <strong class="text-hotel-text">#{{ $reservasi->id_reservasi }}</strong></span>
            <span>{{ Carbon\Carbon::now()->translatedFormat('d M Y, H:i') }}</span>
        </div>

        {{-- Data Tamu --}}
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold">Data Tamu</p>
            <div class="flex justify-between">
                <span class="text-stone-500">Nama</span>
                <span class="font-semibold text-hotel-text">{{ $reservasi->tamu->nama_lengkap }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-500">{{ $reservasi->tamu->tipe_identitas ?? 'Identitas' }}</span>
                <span class="font-mono text-hotel-text">{{ $reservasi->tamu->no_identitas ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-500">Kontak</span>
                <span class="text-hotel-text">{{ $reservasi->tamu->kontak ?? '-' }}</span>
            </div>
        </div>

        {{-- Detail Menginap --}}
        <div class="space-y-2 border-t border-dashed border-stone-200 pt-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold">Detail Menginap</p>
            <div class="flex justify-between">
                <span class="text-stone-500">Check-in</span>
                <span class="text-hotel-text">
                    {{ Carbon\Carbon::parse($reservasi->tgl_checkin)->translatedFormat('d M Y') }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-500">Check-out</span>
                <span class="text-hotel-text">
                    {{ Carbon\Carbon::parse($reservasi->tgl_checkout)->translatedFormat('d M Y') }}
                </span>
            </div>
            @foreach($reservasi->detailKamar as $detail)
            <div class="flex justify-between">
                <span class="text-stone-500">Kamar</span>
                <span class="text-hotel-text">
                    {{ $detail->kamar->nomor_kamar }}
                    <span class="text-stone-400 text-xs">
                        ({{ $detail->kamar->tipeKamar->nama_tipe ?? '-' }})
                    </span>
                </span>
            </div>
            @endforeach
        </div>

        {{-- Rincian Tagihan --}}
        <div class="space-y-2 border-t border-dashed border-stone-200 pt-4">
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold">Rincian Tagihan</p>
            <div class="flex justify-between">
                <span class="text-stone-500">Biaya Kamar</span>
                <span class="text-hotel-text">
                    Rp {{ number_format($reservasi->biaya_kamar, 0, ',', '.') }}
                </span>
            </div>
            @if($reservasi->jam_terlambat > 0)
            <div class="flex justify-between">
                <span class="text-red-500">
                    Charge Keterlambatan ({{ $reservasi->jam_terlambat }} jam)
                </span>
                <span class="text-red-600 font-semibold">
                    Rp {{ number_format($reservasi->biaya_charge, 0, ',', '.') }}
                </span>
            </div>
            @endif
        </div>

        {{-- Total --}}
        <div class="bg-hotel-dark rounded-xl px-5 py-4 flex justify-between items-center">
            <span class="text-stone-300 font-semibold text-sm">TOTAL TAGIHAN</span>
            <span class="text-hotel-gold font-bold text-xl">
                Rp {{ number_format($reservasi->total_tagihan, 0, ',', '.') }}
            </span>
        </div>

        {{-- Status Lunas --}}
        <div class="flex justify-center">
            <span class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold px-4 py-2 rounded-full">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                LUNAS
            </span>
        </div>

        {{-- Footer --}}
        <div class="text-center border-t border-dashed border-stone-200 pt-4">
            <p class="text-xs text-stone-400">Terima kasih telah menginap di RBPL Hotel.</p>
            <p class="text-xs text-stone-300 mt-1">Sampai jumpa kembali!</p>
        </div>

    </div>
</div>

@endsection