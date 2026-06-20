<x-app-layout>
    <div class="py-8 px-4 sm:px-8" style="background:#FAF9F6; min-height:100vh; font-family:'Montserrat',sans-serif;">

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
                    <h1 class="text-xl font-bold" style="color:#1A1A1A;">
                        Jadwal Kerja Karyawan
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Monitoring jadwal kerja karyawan hotel
                    </p>
                </div>
            </div>

            {{-- Filter --}}
            <form method="GET"
                  action="{{ route('hrd.jadwalkerja') }}"
                  class="flex flex-wrap gap-3 mb-6 items-end">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1"
                           style="color:#888;">
                        Pegawai
                    </label>
                    <select name="id_pegawai"
                            class="rounded-lg border px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#e2e0d8; background:#FAF9F6;">
                        <option value="">Semua Karyawan</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}"
                                {{ request('id_pegawai') == $p->id_pegawai ? 'selected' : '' }}>
                                {{ $p->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1"
                           style="color:#888;">
                        Divisi
                    </label>
                    <select name="id_divisi"
                            class="rounded-lg border px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#e2e0d8; background:#FAF9F6;">
                        <option value="">Semua Divisi</option>

                        @foreach($divisis as $d)
                            <option value="{{ $d->id_divisi }}"
                                {{ request('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition"
                        style="background:#D4AF37; color:#1A1A1A;">
                    Filter
                </button>
            </form>

            {{-- Tabel --}}
            @if($jadwals->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="text-sm">
                        Tidak ada data jadwal kerja.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#1A1A1A; color:#D4AF37;">
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Nama Pegawai</th>
                                <th class="px-4 py-3 text-left">Divisi</th>
                                <th class="px-4 py-3 text-left">Jabatan</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Hari</th>
                                <th class="px-4 py-3 text-left">Shift</th>
                                <th class="px-4 py-3 text-left">Jam Kerja</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($jadwals as $i => $j)
                                <tr class="border-b hover:bg-amber-50 transition"
                                    style="border-color:#f0ede6;">

                                    <td class="px-4 py-3">
                                        {{ $i + 1 }}
                                    </td>

                                    <td class="px-4 py-3 font-medium">
                                        {{ $j->pegawai->nama_lengkap }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $j->pegawai->divisi->nama_divisi ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $j->pegawai->jabatan->nama_jabatan ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('l') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                              style="background:#fdf3d0; color:#8a6d00;">
                                            {{ $j->shift->nama_shift }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ substr($j->shift->jam_mulai,0,5) }}
                                        -
                                        {{ substr($j->shift->jam_selesai,0,5) }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>