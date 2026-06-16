@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.hrd')
@section('content')

    {{-- Header halaman --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-hotel-dark">Pengajuan Cuti & Izin</h1>
            <p class="text-xs text-stone-500 mt-0.5">Kelola persetujuan cuti karyawan</p>
        </div>
    </div>

    {{-- Tab status --}}
    <div class="flex gap-2">
        @foreach (['Pending' => 'yellow', 'Approved' => 'emerald', 'Rejected' => 'red'] as $tab => $color)
            <a href="{{ route('hrd.cuti.index', ['status' => $tab]) }}"
               class="flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold border transition
                      {{ $status === $tab
                          ? 'bg-hotel-dark text-hotel-gold border-hotel-dark'
                          : 'bg-white text-stone-500 border-stone-200 hover:bg-stone-50' }}">
                {{ $tab }}
                <span class="bg-{{ $color }}-100 text-{{ $color }}-700 px-2 py-0.5 rounded-full text-[10px]">
                    {{ $counts[$tab] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-stone-100 text-[11px] uppercase tracking-wider text-stone-400">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Nama Karyawan</th>
                    <th class="px-5 py-3 text-left">Tanggal Mulai</th>
                    <th class="px-5 py-3 text-left">Tanggal Selesai</th>
                    <th class="px-5 py-3 text-left">Durasi</th>
                    <th class="px-5 py-3 text-left">Alasan</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($pengajuan as $item)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-5 py-3 text-stone-400 text-xs">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-semibold text-hotel-dark">
                            {{ $item->pegawai->nama_lengkap }}
                        </td>
                        <td class="px-5 py-3 text-stone-600">
                            {{ $item->tanggal_mulai->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-stone-600">
                            {{ $item->tanggal_selesai->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="bg-stone-100 text-stone-600 text-xs px-2 py-1 rounded-full">
                                {{ $item->durasi_hari }} hari
                            </span>
                        </td>
                        <td class="px-5 py-3 text-stone-500 text-xs">
                            {{ Str::limit($item->alasan, 40) }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('hrd.cuti.show', $item->id_cuti) }}"
                               class="text-xs font-semibold text-hotel-gold hover:underline">
                                Lihat Detail →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-stone-400 text-sm">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-stone-300"></i>
                            Tidak ada pengajuan dengan status <strong>{{ $status }}</strong>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($pengajuan->hasPages())
            <div class="px-5 py-3 border-t border-stone-100 text-xs">
                {{ $pengajuan->links() }}
            </div>
        @endif
    </div>

@endsection