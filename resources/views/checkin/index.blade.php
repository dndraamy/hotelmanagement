@extends('layouts.reservasi')

@section('content')

{{-- PAGE HEADER --}}
<div>
    <h1 class="text-2xl font-bold text-hotel-text">Layanan Resepsionis</h1>
    <p class="text-sm text-stone-500 mt-1">Kelola proses check-in dan check-out tamu hotel.</p>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white rounded-2xl p-5 border border-stone-200 flex justify-between items-center">
        <div>
            <p class="text-[11px] uppercase tracking-widest text-stone-400 font-semibold">Siap Check-In</p>
            <p class="text-3xl font-bold text-hotel-gold mt-1">{{ $totalConfirmed }}</p>
            <p class="text-xs text-stone-400 mt-1">Reservasi berstatus Confirmed</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-hotel-gold/10 flex items-center justify-center">
            <i data-lucide="log-in" class="w-6 h-6 text-hotel-gold"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-stone-200 flex justify-between items-center">
        <div>
            <p class="text-[11px] uppercase tracking-widest text-stone-400 font-semibold">Sedang Check-In</p>
            <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $totalCheckedIn }}</p>
            <p class="text-xs text-stone-400 mt-1">Tamu yang sedang menginap</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
            <i data-lucide="bed-double" class="w-6 h-6 text-emerald-500"></i>
        </div>
    </div>
</div>

{{-- TAB NAV --}}
<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    <div class="border-b border-stone-100 flex">
        <button id="tab-checkin-btn"
            onclick="switchTab('checkin')"
            class="tab-btn flex items-center gap-2 px-6 py-4 text-sm font-semibold border-b-2 border-hotel-gold text-hotel-text transition">
            <i data-lucide="log-in" class="w-4 h-4"></i>
            Check-In
            <span class="ml-1 bg-hotel-gold text-hotel-dark text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $totalConfirmed }}</span>
        </button>
        <button id="tab-checkout-btn"
            onclick="switchTab('checkout')"
            class="tab-btn flex items-center gap-2 px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-stone-400 hover:text-hotel-text transition">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Check-Out
            <span class="ml-1 bg-stone-200 text-stone-600 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $totalCheckedIn }}</span>
        </button>
    </div>

    {{-- TAB 1: CHECK-IN --}}
    <div id="tab-checkin" class="tab-panel">
        <div class="px-6 py-3 bg-hotel-gold/5 border-b border-stone-100">
            <p class="text-xs text-stone-500">Reservasi berstatus <strong>Confirmed</strong> — klik <em>Proses Check-In</em> untuk memasukkan identitas tamu dan mengubah status kamar menjadi <strong>Terisi</strong>.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-hotel-dark text-stone-300">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">ID</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Nama Tamu</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Kamar</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Tgl Check-In</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Tgl Check-Out</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Status</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($reservasiPending as $item)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-5 py-4 text-stone-500 font-mono text-xs">#{{ $item->id_reservasi }}</td>
                        <td class="px-5 py-4 font-semibold text-hotel-text">{{ $item->tamu->nama_lengkap }}</td>
                        <td class="px-5 py-4 text-stone-600">
                            @forelse($item->detailKamar as $dk)
                                <span class="inline-block bg-stone-100 text-stone-700 text-xs font-medium px-2 py-0.5 rounded mr-1">
                                    {{ $dk->kamar->nomor_kamar ?? '-' }}
                                </span>
                            @empty
                                <span class="text-stone-400 text-xs">—</span>
                            @endforelse
                        </td>
                        <td class="px-5 py-4 text-stone-600">{{ \Carbon\Carbon::parse($item->tgl_checkin)->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-stone-600">{{ \Carbon\Carbon::parse($item->tgl_checkout)->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-hotel-gold/10 text-yellow-700">Confirmed</span>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('checkin.show', $item->id_reservasi) }}"
                               class="inline-flex items-center gap-1.5 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-xs font-bold px-4 py-2 rounded-xl transition">
                                <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                                Proses Check-In
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-14 text-center text-stone-400">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                            <p>Tidak ada reservasi yang siap check-in</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB 2: CHECK-OUT --}}
    <div id="tab-checkout" class="tab-panel hidden">
        <div class="px-6 py-3 bg-emerald-50 border-b border-stone-100">
            <p class="text-xs text-stone-500">Tamu berstatus <strong>Checked-In</strong> — klik <em>Proses Check-Out</em> untuk melihat rincian tagihan dan memproses pembayaran.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-hotel-dark text-stone-300">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">ID</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Nama Tamu</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Kamar</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Tgl Check-In</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Tgl Check-Out</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Status</th>
                        <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($reservasiCheckedIn as $item)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-5 py-4 text-stone-500 font-mono text-xs">#{{ $item->id_reservasi }}</td>
                        <td class="px-5 py-4 font-semibold text-hotel-text">{{ $item->tamu->nama_lengkap }}</td>
                        <td class="px-5 py-4 text-stone-600">
                            @forelse($item->detailKamar as $dk)
                                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-0.5 rounded mr-1">
                                    {{ $dk->kamar->nomor_kamar ?? '-' }}
                                </span>
                            @empty
                                <span class="text-stone-400 text-xs">—</span>
                            @endforelse
                        </td>
                        <td class="px-5 py-4 text-stone-600">{{ \Carbon\Carbon::parse($item->tgl_checkin)->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-stone-600">{{ \Carbon\Carbon::parse($item->tgl_checkout)->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Checked-In</span>
                        </td>
                        <td class="px-5 py-4">
                            {{-- Link ke halaman preview tagihan (PBI-36 & 37) --}}
                            <a href="{{ route('checkin.checkout', $item->id_reservasi) }}"
                               class="inline-flex items-center gap-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold px-4 py-2 rounded-xl transition">
                                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                Proses Check-Out
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-14 text-center text-stone-400">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                            <p>Tidak ada tamu yang sedang check-in</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        const panels = document.querySelectorAll('.tab-panel');
        const buttons = document.querySelectorAll('.tab-btn');

        panels.forEach(p => p.classList.add('hidden'));
        buttons.forEach(b => {
            b.classList.remove('border-hotel-gold', 'text-hotel-text');
            b.classList.add('border-transparent', 'text-stone-400');
        });

        document.getElementById('tab-' + tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tab + '-btn');
        activeBtn.classList.add('border-hotel-gold', 'text-hotel-text');
        activeBtn.classList.remove('border-transparent', 'text-stone-400');
    }

    lucide.createIcons();
</script>
@endpush

@endsection