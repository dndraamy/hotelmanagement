@extends('layouts.reservasi')

@section('content')

{{-- PAGE HEADER --}}
<div>
    <h1 class="text-2xl font-bold text-hotel-text">Dashboard Reservasi</h1>
    <p class="text-sm text-stone-500 mt-1">Monitor dan kelola seluruh data reservasi tamu hotel.</p>
</div>

{{-- STATISTICS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl p-5 border border-stone-200 flex justify-between items-center">
        <div>
            <p class="text-[11px] uppercase tracking-widest text-stone-400 font-semibold">Total Reservasi</p>
            <p class="text-3xl font-bold text-hotel-text mt-1">{{ $totalReservasi }}</p>
        </div>
        <i data-lucide="calendar" class="w-8 h-8 text-hotel-gold opacity-60"></i>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-stone-200 flex justify-between items-center">
        <div>
            <p class="text-[11px] uppercase tracking-widest text-stone-400 font-semibold">Confirmed</p>
            <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $confirmedReservasi }}</p>
        </div>
        <i data-lucide="check-circle" class="w-8 h-8 text-emerald-400 opacity-60"></i>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-stone-200 flex justify-between items-center">
        <div>
            <p class="text-[11px] uppercase tracking-widest text-stone-400 font-semibold">Pending</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $pendingReservasi }}</p>
        </div>
        <i data-lucide="clock" class="w-8 h-8 text-yellow-400 opacity-60"></i>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-stone-200 flex justify-between items-center">
        <div>
            <p class="text-[11px] uppercase tracking-widest text-stone-400 font-semibold">Cancelled</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $cancelledReservasi }}</p>
        </div>
        <i data-lucide="x-circle" class="w-8 h-8 text-red-400 opacity-60"></i>
    </div>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-hotel-text">Daftar Reservasi Terbaru</h3>
            <p class="text-xs text-stone-400 mt-0.5">Semua data reservasi tamu</p>
        </div>
        <a href="{{ route('reservasi.create') }}"
           class="flex items-center gap-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-xs font-bold px-4 py-2 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Reservasi
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-hotel-dark text-stone-300">
                <tr>
                    <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">ID</th>
                    <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Nama Tamu</th>
                    <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Check-in</th>
                    <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Check-out</th>
                    <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Status</th>
                    <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($reservasi as $item)
                <tr class="hover:bg-stone-50 transition">
                    <td class="px-5 py-4 text-stone-500 font-mono text-xs">#{{ $item->id_reservasi }}</td>
                    <td class="px-5 py-4 font-semibold text-hotel-text">{{ $item->nama_lengkap }}</td>
                    <td class="px-5 py-4 text-stone-600">{{ $item->tgl_checkin }}</td>
                    <td class="px-5 py-4 text-stone-600">{{ $item->tgl_checkout }}</td>
                    <td class="px-5 py-4">
                        @if($item->status_reservasi == 'confirmed')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Confirmed</span>
                        @elseif($item->status_reservasi == 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Pending</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Cancelled</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 flex gap-2">
                        <a href="{{ route('reservasi.show', $item->id_reservasi) }}"
                           class="flex items-center gap-1 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                            <i data-lucide="eye" class="w-3 h-3"></i> Detail
                        </a>
                        <a href="{{ route('reservasi.edit', $item->id_reservasi) }}"
                           class="flex items-center gap-1 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-xs font-medium px-3 py-1.5 rounded-lg transition">
                            <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                        </a>
                        <form action="{{ route('reservasi.destroy', $item->id_reservasi) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus reservasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-stone-400">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                        <p>Tidak ada data reservasi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection