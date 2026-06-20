@extends('layouts.pegawai')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .font-montserrat, .font-montserrat * {
        font-family: 'Montserrat', sans-serif !important;
    }
</style>

<div class="font-montserrat bg-[#FAF9F6] min-h-screen pt-10 pb-16 px-4">
    
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-[#C5A880] mb-10">
        <h2 class="text-3xl font-bold text-[#2D2D2D] mb-8">Form Pengajuan Cuti / Izin</h2>

        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-[#2D2D2D] bg-[#C5A880] rounded-lg font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-6 text-sm text-white bg-red-600 rounded-lg font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('pegawai.cuti.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                <div>
                    <label for="tanggal_mulai" class="block mb-2 text-sm font-semibold text-[#2D2D2D]">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" class="bg-white border border-[#C5A880] text-[#2D2D2D] text-sm rounded-lg focus:ring-[#D4AF37] focus:border-[#D4AF37] block w-full p-2.5 transition duration-150" required>
                    @error('tanggal_mulai')
                        <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block mb-2 text-sm font-semibold text-[#2D2D2D]">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="bg-white border border-[#C5A880] text-[#2D2D2D] text-sm rounded-lg focus:ring-[#D4AF37] focus:border-[#D4AF37] block w-full p-2.5 transition duration-150" required>
                    @error('tanggal_selesai')
                        <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-8">
                <label for="alasan" class="block mb-2 text-sm font-semibold text-[#2D2D2D]">Alasan Cuti/Izin</label>
                <textarea name="alasan" id="alasan" rows="4" class="block p-3 w-full text-sm text-[#2D2D2D] bg-white rounded-lg border border-[#C5A880] focus:ring-[#D4AF37] focus:border-[#D4AF37] transition duration-150" placeholder="Tuliskan alasan pengajuan..." required>{{ old('alasan') }}</textarea>
                @error('alasan')
                    <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="text-white bg-[#D4AF37] hover:bg-[#A9892B] font-semibold rounded-lg text-sm px-6 py-3 text-center transition duration-200 shadow-md">
                    Ajukan Sekarang
                </button>
            </div>
        </form>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-[#C5A880]">
        <h3 class="text-2xl font-bold text-[#2D2D2D] mb-6">Status & Riwayat Pengajuan</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-[#2D2D2D]">
                <thead class="text-xs uppercase bg-[#FAF9F6] border-b border-[#C5A880] text-[#2D2D2D]">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-bold">No</th>
                        <th scope="col" class="px-6 py-3 font-bold">Tanggal Mulai</th>
                        <th scope="col" class="px-6 py-3 font-bold">Tanggal Selesai</th>
                        <th scope="col" class="px-6 py-3 font-bold">Durasi</th>
                        <th scope="col" class="px-6 py-3 font-bold">Alasan</th>
                        <th scope="col" class="px-6 py-3 font-bold text-center">Status Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatCuti as $key => $cuti)
                        <tr class="bg-white border-b border-gray-100 hover:bg-[#FAF9F6] transition">
                            <td class="px-6 py-4 font-medium">{{ $key + 1 }}</td>
                            <td class="px-6 py-4">{{ $cuti->tanggal_mulai->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $cuti->tanggal_selesai->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-medium">{{ $cuti->durasi_hari }} Hari</td>
                            <td class="px-6 py-4 text-gray-600 truncate max-w-xs">{{ $cuti->alasan }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($cuti->status_approval == 'Pending')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FAF9F6] border border-[#D4AF37] text-[#A9892B]">
                                        Pending
                                    </span>
                                @elseif($cuti->status_approval == 'Approved')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-50 border border-green-500 text-green-700">
                                        Approved
                                    </span>
                                @elseif($cuti->status_approval == 'Rejected')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-50 border border-red-500 text-red-700">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                Belum ada riwayat pengajuan cuti atau izin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');

        tanggalMulai.addEventListener('change', function() {
            if (this.value) {
                tanggalSelesai.min = this.value;
                if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                    tanggalSelesai.value = this.value;
                }
            }
        });
    });
</script>
@endsection