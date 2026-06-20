@extends('layouts.kas')

@section('content')

<div class="py-12">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Notifications -->
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800 flex items-center space-x-2" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800 flex items-center space-x-2" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Column 1: Reservation List Selection -->
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700 flex flex-col h-fit">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center space-x-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Reservasi Aktif</span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pilih reservasi tamu untuk mengelola tagihan.</p>
                    </div>

                    <div class="p-4 overflow-y-auto max-h-[500px] divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($reservations as $res)
                            @php
                                $isSelected = $selectedReservasi && $selectedReservasi->id_reservasi == $res->id_reservasi;
                                $firstRoom = $res->detailKamar->first()->kamar ?? null;
                            @endphp
                            <a href="{{ route('penggabungan-tagihan.index', ['id_reservasi' => $res->id_reservasi]) }}" 
                               class="block p-4 transition-all duration-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750 {{ $isSelected ? 'bg-indigo-50/70 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800' : '' }} mb-2 group">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                        {{ $res->tamu->nama_lengkap }}
                                    </span>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $res->status_reservasi === 'Checked-In' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                        {{ $res->status_reservasi }}
                                    </span>
                                </div>
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex justify-between">
                                    <span>
                                        Kamar: <strong class="text-gray-700 dark:text-gray-300">{{ $firstRoom ? $firstRoom->nomor_kamar : 'N/A' }}</strong> 
                                        ({{ $firstRoom && $firstRoom->tipeKamar ? $firstRoom->tipeKamar->nama_tipe : 'N/A' }})
                                    </span>
                                    <span>
                                        {{ \Carbon\Carbon::parse($res->tgl_checkin)->format('d M') }} - {{ \Carbon\Carbon::parse($res->tgl_checkout)->format('d M') }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">Tidak ada reservasi aktif saat ini.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Column 2 & 3: Workspace -->
                <div class="lg:col-span-2 space-y-6">
                    @if($selectedReservasi)
                        <!-- Guest & Stay details card -->
                        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Workspace Penggabungan: {{ $selectedReservasi->tamu->nama_lengkap }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">ID Reservasi: #{{ $selectedReservasi->id_reservasi }} | Kontak: {{ $selectedReservasi->tamu->kontak }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 inline-flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        <span>Cetak Invoice</span>
                                    </button>
                                </div>
                            </div>

                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Stay details info -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Rincian Kamar</h4>
                                    <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-xl space-y-3 text-sm text-gray-600 dark:text-gray-350">
                                        <div class="flex justify-between">
                                            <span>Tanggal Check-in:</span>
                                            <span class="font-semibold text-gray-800 dark:text-gray-250">{{ \Carbon\Carbon::parse($selectedReservasi->tgl_checkin)->format('d F Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Tanggal Check-out:</span>
                                            <span class="font-semibold text-gray-800 dark:text-gray-250">{{ \Carbon\Carbon::parse($selectedReservasi->tgl_checkout)->format('d F Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Durasi Menginap:</span>
                                            <span class="font-semibold text-gray-800 dark:text-gray-250">{{ $nights }} Malam</span>
                                        </div>
                                        @foreach($selectedReservasi->detailKamar as $det)
                                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 space-y-1">
                                                <div class="flex justify-between">
                                                    <span>No. Kamar:</span>
                                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">Kamar {{ $det->kamar->nomor_kamar ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between text-xs">
                                                    <span>Tipe:</span>
                                                    <span>{{ $det->kamar->tipeKamar->nama_tipe ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between text-xs">
                                                    <span>Tarif/Malam:</span>
                                                    <span>Rp {{ number_format($det->kamar->tipeKamar->harga_per_malam ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between font-bold text-gray-800 dark:text-gray-200">
                                            <span>Subtotal Kamar:</span>
                                            <span>Rp {{ number_format($calculatedRoomTotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Tagihan Tambahan List -->
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider pt-2">Layanan Tambahan Hotel</h4>
                                    <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-xl text-sm space-y-2">
                                        @forelse($selectedReservasi->tagihanTambahan as $tambahan)
                                            <div class="flex justify-between text-gray-600 dark:text-gray-350">
                                                <span>{{ $tambahan->masterBiayaTambahan->nama_biaya }}</span>
                                                <span>Rp {{ number_format($tambahan->nominal_akhir, 0, ',', '.') }}</span>
                                            </div>
                                        @empty
                                            <p class="text-xs text-gray-500 dark:text-gray-400 italic">Tidak ada biaya tambahan layanan hotel.</p>
                                        @endforelse
                                        @if(!$selectedReservasi->tagihanTambahan->isEmpty())
                                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between font-bold text-gray-800 dark:text-gray-200">
                                                <span>Subtotal Tambahan:</span>
                                                <span>Rp {{ number_format($selectedReservasi->tagihanTambahan->sum('nominal_akhir'), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Invoice totals card -->
                                <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white p-6 rounded-2xl flex flex-col justify-between shadow-xl">
                                    <div>
                                        <div class="flex justify-between items-center mb-6">
                                            <h4 class="text-xs font-bold uppercase tracking-widest text-indigo-300">Ringkasan Billing</h4>
                                            <span class="text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider {{ $tagihan && $tagihan->status_tagihan === 'Lunas' ? 'bg-emerald-500/25 text-emerald-300 border border-emerald-500/40' : 'bg-amber-500/25 text-amber-300 border border-amber-500/40' }}">
                                                {{ $tagihan ? $tagihan->status_tagihan : 'Belum Lunas' }}
                                            </span>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="flex justify-between text-sm text-slate-300 border-b border-white/10 pb-2">
                                                <span>Total Kamar:</span>
                                                <span class="font-semibold text-white">Rp {{ number_format($tagihan ? $tagihan->total_kamar : $calculatedRoomTotal, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm text-slate-300 border-b border-white/10 pb-2">
                                                <span>Total Layanan Restoran (POS):</span>
                                                <span class="font-semibold text-white">Rp {{ number_format($tagihan ? $tagihan->total_restoran : 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm text-slate-300 border-b border-white/10 pb-2">
                                                <span>Total Tambahan:</span>
                                                <span class="font-semibold text-white">Rp {{ number_format($tagihan ? $tagihan->total_tambahan : $selectedReservasi->tagihanTambahan->sum('nominal_akhir'), 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 border-t-2 border-white/20 pt-4">
                                        <div class="flex justify-between items-baseline mb-2">
                                            <span class="text-sm font-semibold text-indigo-200">Grand Total Tagihan:</span>
                                            <span class="text-3xl font-black text-white">Rp {{ number_format($tagihan ? $tagihan->grand_total : ($calculatedRoomTotal + $selectedReservasi->tagihanTambahan->sum('nominal_akhir')), 0, ',', '.') }}</span>
                                        </div>
                                        <p class="text-slate-400 text-[10px]">Seluruh biaya POS Layanan Restoran berstatus "Charge to Room" telah dimasukkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Restaurant POS Section -->
                        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span>Integrasi POS Layanan Restoran</span>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gabungkan tagihan pesanan restoran pelanggan ke dalam tagihan kamar.</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Tabs/Toggle or sections -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center justify-between">
                                        <span>1. Pesanan Terhubung (Belum Digabungkan)</span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">{{ $pendingOrders->count() }} Pesanan</span>
                                    </h4>

                                    @if($pendingOrders->isEmpty())
                                        <div class="p-4 border border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada pesanan restoran berstatus 'Belum Lunas' yang tertaut langsung dengan reservasi ini.</p>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('penggabungan-tagihan.merge') }}">
                                            @csrf
                                            <input type="hidden" name="id_reservasi" value="{{ $selectedReservasi->id_reservasi }}">
                                            <div class="overflow-x-auto border border-gray-100 dark:border-gray-700 rounded-xl">
                                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                                    <thead class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-450 uppercase text-[10px] tracking-wider font-bold">
                                                        <tr>
                                                            <th scope="col" class="px-6 py-3 text-left w-10">Pilih</th>
                                                            <th scope="col" class="px-6 py-3 text-left">Order ID</th>
                                                            <th scope="col" class="px-6 py-3 text-left">Tanggal</th>
                                                            <th scope="col" class="px-6 py-3 text-left">Menu Details</th>
                                                            <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                                        @foreach($pendingOrders as $order)
                                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750/30 transition-colors">
                                                                <td class="px-6 py-4">
                                                                    <input type="checkbox" name="order_ids[]" value="{{ $order->id_pesanan }}" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 dark:bg-gray-700">
                                                                </td>
                                                                <td class="px-6 py-4 font-mono font-semibold">#{{ $order->id_pesanan }}</td>
                                                                <td class="px-6 py-4 text-xs">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y H:i') }}</td>
                                                                <td class="px-6 py-4">
                                                                    <div class="text-xs space-y-0.5">
                                                                        @foreach($order->detailPesananRestoran as $detail)
                                                                            <div>{{ $detail->qty }}x {{ $detail->itemMenu->nama_item }}</div>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-end">
                                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                    Gabungkan Pesanan Terpilih
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>

                                <hr class="border-gray-100 dark:border-gray-700">

                                <!-- Search and link outside restaurant orders -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        2. Cari Pesanan POS Luar (Unlinked)
                                    </h4>
                                    
                                    <form method="GET" action="{{ route('penggabungan-tagihan.index') }}" class="flex flex-col sm:flex-row gap-3">
                                        <input type="hidden" name="id_reservasi" value="{{ $selectedReservasi->id_reservasi }}">
                                        <div class="relative flex-grow">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" name="search_order_id" value="{{ request('search_order_id') }}" placeholder="Masukkan Order ID Restoran (Contoh: 3)" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-750 dark:bg-gray-800 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-800 dark:text-gray-200">
                                        </div>
                                        <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 shadow-md flex items-center justify-center space-x-1">
                                            <span>Cari Order</span>
                                        </button>
                                    </form>

                                    <!-- Search result displaying here -->
                                    @if(request()->has('search_order_id'))
                                        @if($searchedOrder)
                                            <div class="p-4 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900 rounded-xl space-y-3">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <span class="text-sm font-bold text-gray-800 dark:text-gray-250">Pesanan Ditemukan: Order #{{ $searchedOrder->id_pesanan }}</span>
                                                        <div class="text-xs text-gray-500 dark:text-gray-450 mt-0.5">Tanggal: {{ \Carbon\Carbon::parse($searchedOrder->tanggal_pesanan)->format('d M Y H:i') }}</div>
                                                    </div>
                                                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                                        {{ $searchedOrder->status_pembayaran }}
                                                    </span>
                                                </div>

                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-150 dark:border-gray-700">
                                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Item Pesanan:</div>
                                                    <ul class="text-xs space-y-1 divide-y divide-gray-50 dark:divide-gray-700">
                                                        @foreach($searchedOrder->detailPesananRestoran as $det)
                                                            <li class="flex justify-between py-1">
                                                                <span class="text-gray-700 dark:text-gray-300">{{ $det->qty }}x {{ $det->itemMenu->nama_item }}</span>
                                                                <span class="font-semibold text-gray-800 dark:text-gray-250">Rp {{ number_format($det->subtotal, 0, ',', '.') }}</span>
                                                            </li>
                                                        @endforeach
                                                        <li class="flex justify-between py-2 font-bold text-sm text-gray-900 dark:text-white pt-2 border-t">
                                                            <span>Total Tagihan Restoran:</span>
                                                            <span>Rp {{ number_format($searchedOrder->total_harga, 0, ',', '.') }}</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <form method="POST" action="{{ route('penggabungan-tagihan.merge') }}">
                                                    @csrf
                                                    <input type="hidden" name="id_reservasi" value="{{ $selectedReservasi->id_reservasi }}">
                                                    <input type="hidden" name="order_ids[]" value="{{ $searchedOrder->id_pesanan }}">
                                                    <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all duration-200 shadow-md flex items-center justify-center space-x-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span>Hubungkan & Gabungkan ke Kamar</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-150 dark:border-red-900 rounded-xl">
                                                <p class="text-sm text-red-700 dark:text-red-400">Pesanan dengan ID "{{ request('search_order_id') }}" tidak ditemukan, atau sudah dibayar/digabungkan ke tagihan lain.</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <hr class="border-gray-100 dark:border-gray-700">

                                <!-- Merged Restaurant Orders (Already charged to Room) -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center justify-between">
                                        <span>3. Pesanan yang Telah Digabungkan</span>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">{{ $mergedOrders->count() }} Pesanan</span>
                                    </h4>

                                    @if($mergedOrders->isEmpty())
                                        <div class="p-4 border border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pesanan restoran yang digabungkan ke tagihan kamar ini.</p>
                                        </div>
                                    @else
                                        <div class="overflow-x-auto border border-gray-100 dark:border-gray-700 rounded-xl">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                                <thead class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-450 uppercase text-[10px] tracking-wider font-bold">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left">Order ID</th>
                                                        <th scope="col" class="px-6 py-3 text-left">Tanggal</th>
                                                        <th scope="col" class="px-6 py-3 text-left">Menu Details</th>
                                                        <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                                    @foreach($mergedOrders as $order)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750/30 transition-colors">
                                                            <td class="px-6 py-4 font-mono font-semibold">#{{ $order->id_pesanan }}</td>
                                                            <td class="px-6 py-4 text-xs">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y H:i') }}</td>
                                                            <td class="px-6 py-4">
                                                                <div class="text-xs space-y-0.5">
                                                                    @foreach($order->detailPesananRestoran as $detail)
                                                                        <div>{{ $detail->qty }}x {{ $detail->itemMenu->nama_item }}</div>
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                                            <td class="px-6 py-4 text-center">
                                                                <form method="POST" action="{{ route('penggabungan-tagihan.unmerge') }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penggabungan pesanan ini dari tagihan kamar?')">
                                                                    @csrf
                                                                    <input type="hidden" name="id_reservasi" value="{{ $selectedReservasi->id_reservasi }}">
                                                                    <input type="hidden" name="id_pesanan" value="{{ $order->id_pesanan }}">
                                                                    <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 px-2.5 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
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
                        <!-- No reservation selected empty state -->
                        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-100 dark:border-gray-700 p-12 text-center flex flex-col items-center justify-center space-y-4">
                            <div class="p-4 rounded-full bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="max-w-sm">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Reservasi Terlebih Dahulu</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Silakan pilih salah satu reservasi tamu aktif dari panel sebelah kiri untuk mulai mengelola dan menggabungkan tagihan layanan POS Restoran.</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Print Styles for Invoice printing -->
    <style>
        @media print {
            body {
                background-color: white !important;
                color: black !important;
            }
            header, nav, footer, button, form, hr, .lg\:col-span-1, .no-print {
                display: none !important;
            }
            .lg\:col-span-2 {
                width: 100% !important;
                grid-column: span 3 / span 3 !important;
            }
            .max-w-7xl {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .bg-gradient-to-br {
                background: white !important;
                color: black !important;
                border: 2px solid #ddd !important;
                box-shadow: none !important;
                padding: 1rem !important;
            }
            .bg-gradient-to-br span, .bg-gradient-to-br p, .bg-gradient-to-br h4 {
                color: black !important;
            }
            .bg-gradient-to-br .text-white {
                color: black !important;
            }
            .bg-gradient-to-br .text-3xl {
                color: black !important;
                font-size: 1.5rem !important;
            }
            .shadow-md, .shadow-xl {
                box-shadow: none !important;
            }
            .border {
                border-color: #eee !important;
            }
        }
    </style>
@endsection
