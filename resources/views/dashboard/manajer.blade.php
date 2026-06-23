@extends('layouts.manajer')

@section('content')
<div class="space-y-6">

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-[#1A1A1A] to-[#2D2D2D] rounded-2xl p-8 text-white">
        <h1 class="text-2xl font-bold">
            Selamat Datang, {{ Auth::user()->username ?? Auth::user()->name ?? 'Manajer' }}
        </h1>
        <p class="text-stone-400 mt-2 text-sm">
            Dashboard Manajer Hotel 
        </p>
    </div>

    <!-- Quick Stats -->
    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i data-lucide="file-bar-chart" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-[11px] text-stone-500 uppercase tracking-wider font-semibold">Laporan</p>
                    <p class="text-lg font-bold text-stone-800">Keuangan Detail</p>
                </div>
            </div>
            <a href="{{ route('manajer.laporan-keuangan.index') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-[#D4AF37] hover:text-[#C5A880] transition">
                Buka Laporan Penuh <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

    </div>

    <!-- Tabel Ringkasan Bulanan -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-stone-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-hotel-dark">Tabel Ringkasan Bulanan (Tahun {{ $tahun }})</h3>
                <p class="text-xs text-stone-500 mt-1">Data total pendapatan dan pengeluaran hotel per bulan.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <form action="{{ route('manajer.laporan-keuangan.export-pdf') }}" method="GET" class="flex items-center gap-2">
                    <select name="bulan" class="text-sm border-stone-200 rounded-lg p-2 bg-stone-50">
                        @foreach(range(1, 12) as $b)
                            <option value="{{ $b }}" {{ now()->month == $b ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button type="submit" class="bg-[#D4AF37] hover:bg-[#C5A880] text-hotel-dark font-bold text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Export PDF
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider text-[10px] font-bold">
                        <th class="p-4 border-b border-stone-200">Bulan</th>
                        <th class="p-4 border-b border-stone-200 text-right">Pendapatan</th>
                        <th class="p-4 border-b border-stone-200 text-right">Pengeluaran</th>
                        <th class="p-4 border-b border-stone-200 text-right">Selisih (Laba/Rugi)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($ringkasanTahunan as $r)
                        <tr class="hover:bg-stone-50 transition">
                            <td class="p-4 font-semibold text-stone-800">
                                {{ DateTime::createFromFormat('!m', $r->bulan)->format('F') }}
                            </td>
                            <td class="p-4 text-right text-emerald-600 font-medium">
                                Rp {{ number_format($r->total_pendapatan, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right text-red-600 font-medium">
                                Rp {{ number_format($r->total_pengeluaran, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right font-bold {{ $r->selisih >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                Rp {{ number_format($r->selisih, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-stone-500">
                                Tidak ada data transaksi untuk tahun ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
