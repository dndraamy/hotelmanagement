@extends('layouts.reservasi')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-hotel-text">Detail Reservasi</h1>
    <p class="text-sm text-stone-500 mt-1">Informasi lengkap reservasi #{{ $reservasi->id_reservasi }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-stone-200 p-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wider">Nama Tamu</p>
                    <p class="font-semibold text-hotel-text">{{ $reservasi->tamu->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wider">Status</p>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">{{ $reservasi->status_reservasi }}</span>
                </div>
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wider">Check-in</p>
                    <p class="font-medium">{{ $reservasi->tgl_checkin }}</p>
                </div>
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wider">Check-out</p>
                    <p class="font-medium">{{ $reservasi->tgl_checkout }}</p>
                </div>
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wider">Kamar</p>
                    <p class="font-medium">{{ $reservasi->detailKamar->first()->kamar->nomor_kamar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wider">Tipe Kamar</p>
                    <p class="font-medium">{{ $reservasi->detailKamar->first()->kamar->tipeKamar->nama_tipe ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-stone-200 p-6">
            <h4 class="font-semibold text-hotel-text mb-4">Ringkasan Biaya</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-500">Biaya Kamar</span>
                    <span>Rp {{ number_format($reservasi->biaya_kamar, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-stone-200 pt-2 mt-2">
                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span class="text-hotel-gold">Rp {{ number_format($reservasi->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('reservasi.index') }}"
                    class="block text-center px-4 py-2 border border-stone-300 rounded-xl text-sm hover:bg-stone-50 transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection