<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-500">Reservasi</p>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Dashboard Reservasi</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pantau status tamu, kamar, dan omzet reservasi secara cepat.</p>
            </div>
            <a href="{{ route('reservasi.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                + Tambah Reservasi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4 mb-6">
                <article class="rounded-3xl border border-indigo-100 bg-gradient-to-br from-indigo-600 via-indigo-500 to-blue-500 p-5 text-white shadow-xl shadow-indigo-500/20">
                    <p class="text-sm text-indigo-100">Total Reservasi</p>
                    <p class="mt-3 text-4xl font-bold">{{ $totalReservasi ?? 0 }}</p>
                    <p class="mt-2 text-xs text-indigo-100/90">Jumlah keseluruhan booking aktif dan selesai.</p>
                </article>
                <article class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-500 to-green-500 p-5 text-white shadow-xl shadow-emerald-500/20">
                    <p class="text-sm text-emerald-100">Confirmed</p>
                    <p class="mt-3 text-4xl font-bold">{{ $confirmedReservasi ?? 0 }}</p>
                    <p class="mt-2 text-xs text-emerald-100/90">Reservasi yang sudah dikonfirmasi tamu.</p>
                </article>
                <article class="rounded-3xl border border-amber-100 bg-gradient-to-br from-amber-400 to-orange-400 p-5 text-white shadow-xl shadow-amber-500/20">
                    <p class="text-sm text-amber-100">Pending</p>
                    <p class="mt-3 text-4xl font-bold">{{ $pendingReservasi ?? 0 }}</p>
                    <p class="mt-2 text-xs text-amber-100/90">Booking menunggu proses verifikasi.</p>
                </article>
                <article class="rounded-3xl border border-rose-100 bg-gradient-to-br from-rose-500 to-pink-500 p-5 text-white shadow-xl shadow-rose-500/20">
                    <p class="text-sm text-rose-100">Cancelled</p>
                    <p class="mt-3 text-4xl font-bold">{{ $cancelledReservasi ?? 0 }}</p>
                    <p class="mt-2 text-xs text-rose-100/90">Reservasi yang dibatalkan atau tidak jadi.</p>
                </article>
            </div>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Daftar Reservasi Terbaru</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Tabel ringkasan reservasi hari ini.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800">Live data</span>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Update otomatis</span>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                        <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Nama Tamu</th>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Kamar</th>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Check-in</th>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Check-out</th>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Total Harga</th>
                                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-900">
                            @forelse($reservasi ?? [] as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/80">
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $item->id ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $item->nama_tamu ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $item->nomor_kamar ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $item->tanggal_checkin ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $item->tanggal_checkout ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($item->status_reservasi == 'confirmed')
                                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Confirmed</span>
                                        @elseif($item->status_reservasi == 'pending')
                                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Pending</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-200">Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('reservasi.show', $item->id) }}" class="rounded-lg bg-sky-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-sky-600">Detail</a>
                                            <a href="{{ route('reservasi.edit', $item->id) }}" class="rounded-lg bg-amber-400 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-500">Edit</a>
                                            <form action="{{ route('reservasi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus reservasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-600">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">Tidak ada data reservasi saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>