@extends('layouts.pegawai')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Absensi Karyawan
        </h1>

        <p class="text-gray-500 mt-1">
            Pencatatan jam masuk dan jam pulang kerja
        </p>

    </div>

    {{-- Jadwal Hari Ini --}}
    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <h2 class="font-bold text-lg mb-4">
            Jadwal Hari Ini
        </h2>

        @if($jadwalHariIni)

            <div class="grid md:grid-cols-3 gap-4">

                <div class="bg-blue-50 p-4 rounded-xl">
                    <p class="text-sm text-gray-500">
                        Shift
                    </p>

                    <h3 class="font-bold text-xl">
                        {{ $jadwalHariIni->shift->nama_shift }}
                    </h3>
                </div>

                <div class="bg-green-50 p-4 rounded-xl">
                    <p class="text-sm text-gray-500">
                        Jam Masuk
                    </p>

                    <h3 class="font-bold text-xl">
                        {{ $jadwalHariIni->shift->jam_mulai }}
                    </h3>
                </div>

                <div class="bg-orange-50 p-4 rounded-xl">
                    <p class="text-sm text-gray-500">
                        Jam Pulang
                    </p>

                    <h3 class="font-bold text-xl">
                        {{ $jadwalHariIni->shift->jam_selesai }}
                    </h3>
                </div>

            </div>

        @else

            <div class="text-gray-500">
                Tidak ada jadwal shift hari ini.
            </div>

        @endif

    </div>

    {{-- Absensi --}}
    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <h2 class="font-bold text-lg mb-4">
            Absensi Hari Ini
        </h2>

        <div class="flex flex-wrap gap-3">

            <form action="{{ route('kehadiran.checkin') }}" method="POST">
                @csrf

                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-semibold">

                    Check In

                </button>
            </form>

            <form action="{{ route('kehadiran.checkout') }}" method="POST">
                @csrf

                <button
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-semibold">

                    Check Out

                </button>
            </form>

        </div>

        @if($hariIni)

            <div class="mt-6 grid md:grid-cols-4 gap-4">

                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500">
                        Jam Masuk
                    </p>

                    <h3 class="font-bold">
                        {{ $hariIni->jam_masuk ?? '-' }}
                    </h3>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500">
                        Jam Pulang
                    </p>

                    <h3 class="font-bold">
                        {{ $hariIni->jam_pulang ?? '-' }}
                    </h3>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500">
                        Status
                    </p>

                    <h3 class="font-bold">
                        {{ $hariIni->status_kehadiran }}
                    </h3>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500">
                        Lembur
                    </p>

                    <h3 class="font-bold">
                        {{ $hariIni->menit_lembur }} menit
                    </h3>
                </div>

            </div>

        @endif

    </div>

    {{-- Riwayat --}}
    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <h2 class="font-bold text-lg mb-4">
            Riwayat Kehadiran
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">Tanggal</th>
                        <th class="text-left py-3">Masuk</th>
                        <th class="text-left py-3">Pulang</th>
                        <th class="text-left py-3">Status</th>
                        <th class="text-left py-3">Lembur</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($riwayat as $item)

                    <tr class="border-b">

                        <td class="py-3">
                            {{ $item->tanggal }}
                        </td>

                        <td>
                            {{ $item->jam_masuk }}
                        </td>

                        <td>
                            {{ $item->jam_pulang }}
                        </td>

                        <td>
                            {{ $item->status_kehadiran }}
                        </td>

                        <td>
                            {{ $item->menit_lembur }} menit
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Belum ada data kehadiran.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-4">
            {{ $riwayat->links() }}
        </div>

    </div>

</div>

@endsection