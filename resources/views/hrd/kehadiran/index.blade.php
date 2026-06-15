@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <h1 class="text-2xl font-bold">
            Monitoring Kehadiran Karyawan
        </h1>

        <p class="text-gray-500">
            Rekap absensi seluruh karyawan
        </p>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <form method="GET">

            <div class="grid md:grid-cols-4 gap-4">

                <select
                    name="id_pegawai"
                    class="border rounded-xl px-4 py-3">

                    <option value="">
                        Semua Pegawai
                    </option>

                    @foreach($pegawais as $pegawai)

                        <option
                            value="{{ $pegawai->id_pegawai }}"
                            {{ request('id_pegawai') == $pegawai->id_pegawai ? 'selected' : '' }}>

                            {{ $pegawai->nama_lengkap }}

                        </option>

                    @endforeach

                </select>

                <select
                    name="status"
                    class="border rounded-xl px-4 py-3">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="Hadir">
                        Hadir
                    </option>

                    <option value="Terlambat">
                        Terlambat
                    </option>

                    <option value="Alpha">
                        Alpha
                    </option>

                </select>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                    class="border rounded-xl px-4 py-3">

                <button
                    class="bg-blue-600 text-white rounded-xl px-4 py-3">

                    Filter

                </button>

            </div>

        </form>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                <tr class="border-b">

                    <th class="text-left py-3">
                        Pegawai
                    </th>

                    <th class="text-left py-3">
                        Tanggal
                    </th>

                    <th class="text-left py-3">
                        Masuk
                    </th>

                    <th class="text-left py-3">
                        Pulang
                    </th>

                    <th class="text-left py-3">
                        Status
                    </th>

                    <th class="text-left py-3">
                        Lembur
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($kehadiran as $item)

                    <tr class="border-b">

                        <td class="py-3">
                            {{ $item->pegawai->nama_lengkap }}
                        </td>

                        <td>
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
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Tidak ada data.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-4">
            {{ $kehadiran->links() }}
        </div>

    </div>

</div>

@endsection