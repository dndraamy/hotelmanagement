@extends('layouts.hrd')
@section('content')

    <div class="py-8 px-4 sm:px-8" style="background:#FAF9F6; min-height:100vh; font-family:'Montserrat',sans-serif;">

        {{-- Alert --}}
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
                 style="background:#f0fdf4; border:1px solid #86efac; color:#166534;">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm p-6">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold" style="color:#1A1A1A;">Jadwal Shift Karyawan</h1>
                    <p class="text-sm mt-0.5" style="color:#888;">Kelola jadwal shift seluruh pegawai hotel</p>
                </div>
                <a href="{{ route('hrd.dashboard.hrd.jadwal-shift.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition"
                   style="background:#D4AF37; color:#1A1A1A;">
                    + Tambah Jadwal
                </a>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('hrd.dashboard.hrd.jadwal-shift.index') }}"
                  class="flex flex-wrap gap-3 mb-6 items-end">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#888;">Bulan</label>
                    <select name="bulan" class="rounded-lg border px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#e2e0d8; background:#FAF9F6; color:#2D2D2D;">
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#888;">Tahun</label>
                    <select name="tahun" class="rounded-lg border px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#e2e0d8; background:#FAF9F6; color:#2D2D2D;">
                        @foreach([now()->year-1, now()->year, now()->year+1] as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#888;">Pegawai</label>
                    <select name="id_pegawai" class="rounded-lg border px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#e2e0d8; background:#FAF9F6; color:#2D2D2D;">
                        <option value="">Semua Pegawai</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}" {{ request('id_pegawai') == $p->id_pegawai ? 'selected' : '' }}>
                                {{ $p->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold transition"
                        style="background:#D4AF37; color:#1A1A1A;">
                    Filter
                </button>
            </form>

            {{-- Tabel --}}
            @if($jadwals->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <svg class="mx-auto mb-3 w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">Tidak ada jadwal untuk periode ini.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#1A1A1A; color:#D4AF37;">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Hari</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Pegawai</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Shift</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jam</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwals as $i => $j)
                            <tr class="border-b hover:bg-amber-50 transition" style="border-color:#f0ede6;">
                                <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('l') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $j->pegawai->nama_lengkap }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                          style="background:#fdf3d0; color:#8a6d00;">
                                        {{ $j->shift->nama_shift }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ substr($j->shift->jam_mulai,0,5) }} – {{ substr($j->shift->jam_selesai,0,5) }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('hrd.dashboard.hrd.jadwal-shift.edit', $j->id_jadwal) }}"
                                           class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition"
                                           style="border-color:#D4AF37; color:#D4AF37;">
                                            Edit
                                        </a>
                                        <form action="{{ route('hrd.dashboard.hrd.jadwal-shift.destroy', $j->id_jadwal) }}"
                                              method="POST"
                                              x-data
                                              @submit.prevent="if(confirm('Hapus jadwal ini?')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition"
                                                    style="border-color:#e53e3e; color:#e53e3e;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
