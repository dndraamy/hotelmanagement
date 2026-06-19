<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $penggajian->pegawai->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 flex justify-center text-gray-800 font-sans">

    <div class="bg-white w-full max-w-2xl p-8 border border-gray-200 shadow-sm relative">
        
        <button onclick="window.print()" class="no-print absolute top-4 right-4 bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 text-sm">
            Print PDF
        </button>

        <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
            <h1 class="text-xl font-bold mt-4">SLIP GAJI KARYAWAN</h1>
            <p class="text-sm">Periode: {{ date('F', mktime(0, 0, 0, $penggajian->periode_bulan, 10)) }} {{ $penggajian->periode_tahun }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8 text-sm">
            <div>
                <p><span class="font-semibold inline-block w-32">ID Pegawai</span>: {{ $penggajian->pegawai->id_pegawai ?? '-' }}</p>
                <p><span class="font-semibold inline-block w-32">Nama Lengkap</span>: {{ $penggajian->pegawai->nama_lengkap ?? '-' }}</p>
            </div>
            <div>
                <p><span class="font-semibold inline-block w-32">Jabatan</span>: {{ $penggajian->pegawai->jabatan->nama_jabatan ?? '-' }}</p>
                <p><span class="font-semibold inline-block w-32">Tgl Cetak</span>: {{ \Carbon\Carbon::parse($penggajian->tanggal_cetak_slip)->format('d M Y') }}</p>
            </div>
        </div>

        <table class="w-full text-sm mb-8">
            <thead>
                <tr class="bg-gray-100 border-y border-gray-300">
                    <th class="py-2 px-4 text-left">Deskripsi / Komponen</th>
                    <th class="py-2 px-4 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="py-3 px-4">Gaji Pokok</td>
                    <td class="py-3 px-4 text-right">Rp {{ number_format($penggajian->total_gaji_pokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="py-3 px-4">Uang Lembur (Tambahan)</td>
                    <td class="py-3 px-4 text-right text-green-600">+ Rp {{ number_format($penggajian->total_uang_lembur, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="py-3 px-4">Potongan (Alpha / Izin)</td>
                    <td class="py-3 px-4 text-right text-red-600">- Rp {{ number_format($penggajian->total_potongan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-800 font-bold text-lg">
                    <td class="py-4 px-4 text-right">TOTAL GAJI BERSIH:</td>
                    <td class="py-4 px-4 text-right">Rp {{ number_format($penggajian->gaji_bersih, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-16 flex justify-between text-sm text-center">
            <div>
                <p class="mb-16">Penerima,</p>
                <p class="font-bold border-b border-gray-400 inline-block px-4">{{ $penggajian->pegawai->nama_lengkap ?? '-' }}</p>
            </div>
            <div>
                <p class="mb-16">Mengetahui, HRD</p>
                <p class="font-bold border-b border-gray-400 inline-block px-4">Staf HRD</p>
            </div>
        </div>

    </div>

</body>
</html>