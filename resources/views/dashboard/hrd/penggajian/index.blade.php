@extends('layouts.hrd') @section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kalkulasi Penggajian</h1>
            <p class="text-gray-500">Penghitungan Gaji & Cetak Slip Gaji</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Generate Gaji Bulanan</h2>
        
        <form action="{{ route('hrd.dashboard.hrd.penggajian.generate') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
            @csrf
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Periode Bulan</label>
                <select name="bulan" class="w-full border rounded-xl px-4 py-3 bg-gray-50" required>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Tahun</label>
                <select name="tahun" class="w-full border rounded-xl px-4 py-3 bg-gray-50" required>
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="w-full md:w-1/3">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200" onclick="return confirm('Apakah Anda yakin ingin men-generate gaji untuk periode ini?')">
                    Kalkulasi Gaji
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-bold text-gray-800">Riwayat Penggajian (Periode: {{ $bulan }}/{{ $tahun }})</h3>
            
            <form action="{{ route('hrd.dashboard.hrd.penggajian.index') }}" method="GET" class="flex gap-2">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Refresh Data</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-4 font-semibold text-gray-600">Nama Pegawai</th>
                        <th class="p-4 font-semibold text-gray-600">Jabatan</th>
                        <th class="p-4 font-semibold text-gray-600">Gaji Pokok</th>
                        <th class="p-4 font-semibold text-green-600">Lembur</th>
                        <th class="p-4 font-semibold text-red-600">Potongan</th>
                        <th class="p-4 font-semibold text-gray-800">Gaji Bersih</th>
                        <th class="p-4 font-semibold text-center text-gray-800">Aksi</th> </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatGaji as $gaji)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-gray-800">{{ $gaji->pegawai->nama_lengkap ?? '-' }}</td>
                            <td class="p-4 text-gray-500">{{ $gaji->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                            <td class="p-4 text-gray-600">Rp {{ number_format($gaji->total_gaji_pokok, 0, ',', '.') }}</td>
                            <td class="p-4 text-green-600">Rp {{ number_format($gaji->total_uang_lembur, 0, ',', '.') }}</td>
                            <td class="p-4 text-red-600">Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                            <td class="p-4 font-bold text-gray-800">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <a href="{{ route('hrd.dashboard.hrd.penggajian.cetak', $gaji->id_penggajian) }}" target="_blank" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1">
                                    Cetak Slip
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">
                                Belum ada data kalkulasi gaji untuk periode ini.<br>
                                Silakan gunakan form di atas untuk men-generate data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection