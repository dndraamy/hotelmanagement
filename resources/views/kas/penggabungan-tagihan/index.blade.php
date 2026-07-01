@extends(auth()->user()->hasRole('Manajer Hotel') ? 'layouts.manajer' : 'layouts.kas')

@section('content')

<style>
    /* ── PRD v2.1 Cream Theme Tokens ── */
    .pgt-page        { background-color: #F7F2E7; }
    .pgt-card        { background-color: #FFFFFF; border: 1px solid #E8DFC8; border-radius: 10px; box-shadow: 0 1px 3px rgba(58,46,30,0.08); }
    .pgt-card-header { background-color: #FDFBF7; border-bottom: 1px solid #E8DFC8; }
    .pgt-heading     { color: #3A2E1E; }
    .pgt-body        { color: #5A5347; }
    .pgt-gold        { color: #C9973A; }
    .pgt-gold-dark   { color: #B8860B; }

    /* Badges */
    .pgt-badge-unpaid   { background-color: #F1D9A0; color: #7A5B12; }
    .pgt-badge-checkin   { background-color: #DCEFE0; color: #2E7D46; }
    .pgt-badge-pending   { background-color: #FDF0D5; color: #7A5B12; border: 1px solid #E8D5A0; }
    .pgt-badge-merged    { background-color: #DCEFE0; color: #2E7D46; border: 1px solid #B8DBBF; }

    /* Buttons */
    .pgt-btn-primary  { background-color: #C9973A; color: #FFFFFF; border: none; }
    .pgt-btn-primary:hover { background-color: #B8860B; }
    .pgt-btn-merge    { background-color: #C9973A; color: #FFFFFF; }
    .pgt-btn-merge:hover { background-color: #B8860B; }
    .pgt-btn-unlink   { color: #B23A3A; }
    .pgt-btn-unlink:hover { background-color: #FDF0F0; color: #8B2E2E; }

    /* Table */
    .pgt-table-head   { background-color: #FDFBF7; color: #5A5347; }
    .pgt-table-row:hover { background-color: #FBF8F1; }

    /* Billing summary */
    .pgt-billing      { background: linear-gradient(135deg, #3A2E1E 0%, #2A1F12 100%); }
    .pgt-billing-label { color: #C9973A; }
    .pgt-billing-value { color: #FFFFFF; }

    /* Selected reservation */
    .pgt-res-selected { background-color: #FDF5E6; border: 1px solid #E8D5A0; }
    .pgt-res-hover:hover { background-color: #FBF8F1; }

    /* Inner detail box */
    .pgt-detail-box   { background-color: #FDFBF7; border: 1px solid #E8DFC8; border-radius: 10px; }

    /* Checkbox gold */
    .pgt-checkbox:checked { accent-color: #C9973A; }

    /* Scrollbar subtle */
    .pgt-scroll::-webkit-scrollbar { width: 5px; }
    .pgt-scroll::-webkit-scrollbar-track { background: transparent; }
    .pgt-scroll::-webkit-scrollbar-thumb { background: #E8DFC8; border-radius: 3px; }
    .pgt-scroll::-webkit-scrollbar-thumb:hover { background: #C9973A; }

    /* ── Single-frame layout: fill viewport ── */
    .pgt-frame {
        height: calc(100vh - 80px); /* minus topbar */
        overflow: hidden;
    }
    .pgt-col-left {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .pgt-col-right {
        height: 100%;
        overflow-y: auto;
    }
</style>

<div class="pgt-page -m-6 -mt-6 p-4 min-h-full">

    {{-- ── Notifications (compact) ── --}}
    @if(session('success'))
        <div class="px-3 py-2 mb-3 text-xs rounded-lg flex items-center space-x-2" style="background-color:#DCEFE0; color:#2E7D46; border:1px solid #B8DBBF;" role="alert">
            <svg class="flex-shrink-0 w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="px-3 py-2 mb-3 text-xs rounded-lg flex items-center space-x-2" style="background-color:#FDF0F0; color:#B23A3A; border:1px solid #E8C8C8;" role="alert">
            <svg class="flex-shrink-0 w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ══ SINGLE-FRAME GRID ══ --}}
    <div class="pgt-frame grid grid-cols-1 lg:grid-cols-12 gap-4">

        {{-- ════════════════════════════════════════════
             LEFT — Reservasi Aktif (narrow sidebar)
             ════════════════════════════════════════════ --}}
        <div class="lg:col-span-3 pgt-col-left">
            <div class="pgt-card flex flex-col h-full overflow-hidden">
                {{-- Header --}}
                <div class="px-4 py-3 pgt-card-header" style="border-radius:10px 10px 0 0;">
                    <h3 class="text-sm font-bold pgt-heading flex items-center space-x-2">
                        <svg class="w-4 h-4 pgt-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Reservasi Aktif</span>
                    </h3>
                    <p class="text-[10px] pgt-body mt-0.5">Pilih reservasi untuk mengelola tagihan.</p>
                </div>
                {{-- Scrollable list --}}
                <div class="flex-1 overflow-y-auto p-2 pgt-scroll">
                    @forelse($reservations as $res)
                        @php
                            $isSelected = $selectedReservasi && $selectedReservasi->id_reservasi == $res->id_reservasi;
                            $firstRoom = $res->detailKamar->first()->kamar ?? null;
                        @endphp
                        <a href="{{ route('penggabungan-tagihan.index', ['id_reservasi' => $res->id_reservasi]) }}"
                           class="block px-3 py-2.5 transition-all duration-200 rounded-lg mb-1.5 group {{ $isSelected ? 'pgt-res-selected' : 'pgt-res-hover' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold pgt-heading group-hover:text-[#C9973A] truncate mr-2">
                                    {{ $res->tamu->nama_lengkap }}
                                </span>
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full whitespace-nowrap {{ $res->status_reservasi === 'Checked-In' ? 'pgt-badge-checkin' : 'pgt-badge-unpaid' }}">
                                    {{ $res->status_reservasi }}
                                </span>
                            </div>
                            <div class="mt-1 text-[10px] pgt-body flex justify-between">
                                <span>Kmr <strong class="pgt-heading">{{ $firstRoom ? $firstRoom->nomor_kamar : '-' }}</strong></span>
                                <span>{{ \Carbon\Carbon::parse($res->tgl_checkin)->format('d/m') }} – {{ \Carbon\Carbon::parse($res->tgl_checkout)->format('d/m') }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs pgt-body text-center py-6">Tidak ada reservasi aktif.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════
             RIGHT — Workspace (main content area)
             ════════════════════════════════════════════ --}}
        <div class="lg:col-span-9 pgt-col-right pgt-scroll">
            @if($selectedReservasi)

                {{-- ─── ROW 1: Rincian Kamar + Billing Summary (side-by-side) ─── --}}
                <div class="pgt-card overflow-hidden mb-4">
                    {{-- Compact header --}}
                    <div class="px-4 py-2.5 pgt-card-header flex items-center justify-between" style="border-radius:10px 10px 0 0;">
                        <div>
                            <h3 class="text-sm font-bold pgt-heading">{{ $selectedReservasi->tamu->nama_lengkap }}</h3>
                            <p class="text-[10px] pgt-body">ID: #{{ $selectedReservasi->id_reservasi }} | {{ $selectedReservasi->tamu->kontak }}</p>
                        </div>
                        <button onclick="window.print()" class="pgt-btn-primary px-3 py-1.5 text-xs font-semibold rounded-lg inline-flex items-center space-x-1.5 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            <span>Cetak Invoice</span>
                        </button>
                    </div>

                    <div class="p-4 grid grid-cols-1 md:grid-cols-5 gap-4">

                        {{-- Left 3/5: Rincian Kamar + Tambahan --}}
                        <div class="md:col-span-3 space-y-3">
                            <h4 class="text-xs font-bold pgt-gold-dark uppercase tracking-wider">Rincian Kamar</h4>
                            <div class="pgt-detail-box p-3 space-y-2 text-xs pgt-body">
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <span class="block text-[10px] pgt-body">Check-in</span>
                                        <span class="font-semibold pgt-heading text-xs">{{ \Carbon\Carbon::parse($selectedReservasi->tgl_checkin)->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] pgt-body">Check-out</span>
                                        <span class="font-semibold pgt-heading text-xs">{{ \Carbon\Carbon::parse($selectedReservasi->tgl_checkout)->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] pgt-body">Durasi</span>
                                        <span class="font-semibold pgt-heading text-xs">{{ $nights }} Malam</span>
                                    </div>
                                </div>
                                @foreach($selectedReservasi->detailKamar as $det)
                                    <div class="flex items-center justify-between pt-2" style="border-top:1px solid #E8DFC8;">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold pgt-gold">Kmr {{ $det->kamar->nomor_kamar ?? 'N/A' }}</span>
                                            <span class="text-[10px] pgt-body">{{ $det->kamar->tipeKamar->nama_tipe ?? '-' }}</span>
                                        </div>
                                        <span class="text-[10px]">Rp {{ number_format($det->kamar->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }}/mlm</span>
                                    </div>
                                @endforeach
                                <div class="pt-2 flex justify-between font-bold pgt-heading" style="border-top:1px solid #E8DFC8;">
                                    <span>Subtotal Kamar</span>
                                    <span class="pgt-badge-unpaid px-2 py-0.5 rounded-full text-[10px]">Rp {{ number_format($calculatedRoomTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Tagihan Tambahan --}}
                            <h4 class="text-xs font-bold pgt-gold-dark uppercase tracking-wider">Layanan Tambahan</h4>
                            <div class="pgt-detail-box p-3 text-xs space-y-1.5">
                                @forelse($selectedReservasi->tagihanTambahan as $tambahan)
                                    <div class="flex justify-between pgt-body">
                                        <span>{{ $tambahan->masterBiayaTambahan->nama_biaya }}</span>
                                        <span>Rp {{ number_format($tambahan->nominal_akhir, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <p class="text-[10px] pgt-body italic">Tidak ada biaya tambahan.</p>
                                @endforelse
                                @if(!$selectedReservasi->tagihanTambahan->isEmpty())
                                    <div class="pt-1.5 flex justify-between font-bold pgt-heading" style="border-top:1px solid #E8DFC8;">
                                        <span>Subtotal Tambahan</span>
                                        <span>Rp {{ number_format($selectedReservasi->tagihanTambahan->sum('nominal_akhir'), 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right 2/5: Ringkasan Billing --}}
                        <div class="md:col-span-2">
                            <div class="pgt-billing p-4 rounded-xl flex flex-col justify-between h-full shadow-lg">
                                <div>
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-[10px] font-bold uppercase tracking-widest pgt-billing-label">Ringkasan Billing</h4>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase"
                                              style="{{ $tagihan && $tagihan->status_tagihan === 'Lunas' ? 'background-color:rgba(46,125,70,0.2);color:#6BCB77;border:1px solid rgba(46,125,70,0.35);' : 'background-color:#F1D9A0;color:#7A5B12;border:1px solid #E8D5A0;' }}">
                                            {{ $tagihan ? $tagihan->status_tagihan : 'Belum Lunas' }}
                                        </span>
                                    </div>
                                    <div class="space-y-2.5 text-xs">
                                        <div class="flex justify-between pb-2" style="border-bottom:1px solid rgba(201,151,58,0.25);">
                                            <span style="color:#D4C5A0;">Total Kamar</span>
                                            <span class="font-semibold pgt-billing-value">Rp {{ number_format($tagihan ? $tagihan->total_kamar : $calculatedRoomTotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between pb-2" style="border-bottom:1px solid rgba(201,151,58,0.25);">
                                            <span style="color:#D4C5A0;">Restoran (POS)</span>
                                            <span class="font-semibold pgt-billing-value">Rp {{ number_format($tagihan ? $tagihan->total_restoran : 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between pb-2" style="border-bottom:1px solid rgba(201,151,58,0.25);">
                                            <span style="color:#D4C5A0;">Tambahan</span>
                                            <span class="font-semibold pgt-billing-value">Rp {{ number_format($tagihan ? $tagihan->total_tambahan : $selectedReservasi->tagihanTambahan->sum('nominal_akhir'), 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3" style="border-top:2px solid rgba(201,151,58,0.4);">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-semibold pgt-billing-label">Grand Total</span>
                                        <span class="text-2xl font-black pgt-billing-value mt-0.5">Rp {{ number_format($tagihan ? $tagihan->grand_total : ($calculatedRoomTotal + $selectedReservasi->tagihanTambahan->sum('nominal_akhir')), 0, ',', '.') }}</span>
                                    </div>
                                    <p style="color:#8B7D6B;" class="text-[9px] mt-1">Semua POS "Charge to Room" telah dimasukkan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── ROW 2: POS Restoran — side-by-side tables ─── --}}
                <div class="pgt-card overflow-hidden">
                    <div class="px-4 py-2.5 pgt-card-header flex items-center space-x-2" style="border-radius:10px 10px 0 0;">
                        <svg class="w-4 h-4 pgt-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-sm font-bold pgt-heading">Integrasi POS Layanan Restoran</h3>
                    </div>

                    <div class="p-4 grid grid-cols-1 lg:grid-cols-2 gap-4">

                        {{-- ── 1. Pesanan Belum Digabungkan ── --}}
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold pgt-heading uppercase tracking-wider">1. Belum Digabungkan</h4>
                                <span class="pgt-badge-pending text-[10px] px-2 py-0.5 rounded-full font-semibold">{{ $pendingOrders->count() }}</span>
                            </div>

                            @if($pendingOrders->isEmpty())
                                <div class="p-3 rounded-lg text-center" style="border:1px dashed #E8DFC8;">
                                    <p class="text-[10px] pgt-body">Tidak ada pesanan restoran berstatus 'Belum Lunas' tertaut.</p>
                                </div>
                            @else
                                <form method="POST" action="{{ route('penggabungan-tagihan.merge') }}">
                                    @csrf
                                    <input type="hidden" name="id_reservasi" value="{{ $selectedReservasi->id_reservasi }}">
                                    <div class="overflow-x-auto rounded-lg max-h-[220px] overflow-y-auto pgt-scroll" style="border:1px solid #E8DFC8;">
                                        <table class="min-w-full divide-y text-[11px]" style="border-color:#E8DFC8;">
                                            <thead class="pgt-table-head uppercase text-[9px] tracking-wider font-bold sticky top-0 z-10">
                                                <tr>
                                                    <th class="px-2 py-2 text-left w-8">✓</th>
                                                    <th class="px-2 py-2 text-left">Order</th>
                                                    <th class="px-2 py-2 text-left">Menu</th>
                                                    <th class="px-2 py-2 text-right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y bg-white pgt-body" style="border-color:#E8DFC8;">
                                                @foreach($pendingOrders as $order)
                                                    <tr class="pgt-table-row transition-colors">
                                                        <td class="px-2 py-2">
                                                            <input type="checkbox" name="order_ids[]" value="{{ $order->id_pesanan }}" checked class="pgt-checkbox w-3.5 h-3.5 rounded">
                                                        </td>
                                                        <td class="px-2 py-2">
                                                            <div class="font-mono font-semibold pgt-heading">#{{ $order->id_pesanan }}</div>
                                                            <div class="text-[9px] pgt-body">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d/m H:i') }}</div>
                                                        </td>
                                                        <td class="px-2 py-2">
                                                            <div class="space-y-0.5">
                                                                @foreach($order->detailPesananRestoran as $detail)
                                                                    <div class="text-[10px]">{{ $detail->qty }}x {{ $detail->itemMenu->nama_item }}</div>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-2 text-right font-semibold pgt-heading whitespace-nowrap">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-2 flex justify-end">
                                        <button type="submit" class="pgt-btn-merge px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                            Gabungkan Terpilih
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>

                        {{-- ── 2. Pesanan yang Telah Digabungkan ── --}}
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold pgt-heading uppercase tracking-wider">2. Sudah Digabungkan</h4>
                                <span class="pgt-badge-merged text-[10px] px-2 py-0.5 rounded-full font-semibold">{{ $mergedOrders->count() }}</span>
                            </div>

                            @if($mergedOrders->isEmpty())
                                <div class="p-3 rounded-lg text-center" style="border:1px dashed #E8DFC8;">
                                    <p class="text-[10px] pgt-body">Belum ada pesanan yang digabungkan ke tagihan kamar.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto rounded-lg max-h-[220px] overflow-y-auto pgt-scroll" style="border:1px solid #E8DFC8;">
                                    <table class="min-w-full divide-y text-[11px]" style="border-color:#E8DFC8;">
                                        <thead class="pgt-table-head uppercase text-[9px] tracking-wider font-bold sticky top-0 z-10">
                                            <tr>
                                                <th class="px-2 py-2 text-left">Order</th>
                                                <th class="px-2 py-2 text-left">Menu</th>
                                                <th class="px-2 py-2 text-right">Total</th>
                                                <th class="px-2 py-2 text-center w-12">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y bg-white pgt-body" style="border-color:#E8DFC8;">
                                            @foreach($mergedOrders as $order)
                                                <tr class="pgt-table-row transition-colors">
                                                    <td class="px-2 py-2">
                                                        <div class="font-mono font-semibold pgt-heading">#{{ $order->id_pesanan }}</div>
                                                        <div class="text-[9px] pgt-body">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d/m H:i') }}</div>
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        <div class="space-y-0.5">
                                                            @foreach($order->detailPesananRestoran as $detail)
                                                                <div class="text-[10px]">{{ $detail->qty }}x {{ $detail->itemMenu->nama_item }}</div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td class="px-2 py-2 text-right font-semibold pgt-heading whitespace-nowrap">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                                    <td class="px-2 py-2 text-center">
                                                        <form method="POST" action="{{ route('penggabungan-tagihan.unmerge') }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penggabungan pesanan ini dari tagihan kamar?')">
                                                            @csrf
                                                            <input type="hidden" name="id_reservasi" value="{{ $selectedReservasi->id_reservasi }}">
                                                            <input type="hidden" name="id_pesanan" value="{{ $order->id_pesanan }}">
                                                            <button type="submit" class="pgt-btn-unlink text-[10px] font-bold px-1.5 py-1 rounded transition-all duration-200">
                                                                Lepas
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @else
                {{-- ─── Empty State ─── --}}
                <div class="pgt-card p-10 text-center flex flex-col items-center justify-center space-y-3 h-full">
                    <div class="p-3 rounded-full" style="background-color:#FDF5E6;">
                        <svg class="w-10 h-10 pgt-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="max-w-sm">
                        <h3 class="text-base font-bold pgt-heading">Pilih Reservasi Terlebih Dahulu</h3>
                        <p class="text-xs pgt-body mt-1.5">Pilih salah satu reservasi tamu dari panel kiri untuk mengelola tagihan POS Restoran.</p>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body { background-color: white !important; color: black !important; }
        header, nav, footer, button, form, hr, .lg\:col-span-3, .no-print { display: none !important; }
        .lg\:col-span-9 { width: 100% !important; grid-column: span 12 / span 12 !important; }
        .max-w-7xl { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .pgt-billing { background: white !important; color: black !important; border: 2px solid #ddd !important; box-shadow: none !important; }
        .pgt-billing span, .pgt-billing p, .pgt-billing h4 { color: black !important; }
        .pgt-billing .pgt-billing-value, .pgt-billing .text-2xl { color: black !important; }
        .pgt-card { box-shadow: none !important; border-color: #eee !important; }
        .pgt-page { background-color: white !important; }
        .pgt-frame { height: auto !important; overflow: visible !important; }
    }
</style>
@endsection
