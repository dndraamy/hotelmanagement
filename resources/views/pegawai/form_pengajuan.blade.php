@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
 <div class="font-['Montserrat'] bg-[#FAF9F6] min-h-screen pt-10 px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-[#C5A880]">
        <h2 class="text-3xl font-bold text-[#2D2D2D] mb-8">Form Pengajuan Cuti / Izin</h2>

        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-[#2D2D2D] bg-[#C5A880] rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-6 text-sm text-white bg-red-600 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('pegawai.cuti.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="tanggal_mulai" class="block mb-2 text-sm font-medium text-[#2D2D2D]">Tanggal Mulai</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-[#2D2D2D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                        </svg>
                    </div>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" class="bg-white border border-[#C5A880] text-[#2D2D2D] text-sm rounded-lg focus:ring-[#D4AF37] focus:border-[#D4AF37] block w-full ps-10 p-2.5 transition duration-150" required>
                </div>
                @error('tanggal_mulai')
                    <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="tanggal_selesai" class="block mb-2 text-sm font-medium text-[#2D2D2D]">Tanggal Selesai</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-[#2D2D2D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                        </svg>
                    </div>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="bg-white border border-[#C5A880] text-[#2D2D2D] text-sm rounded-lg focus:ring-[#D4AF37] focus:border-[#D4AF37] block w-full ps-10 p-2.5 transition duration-150" required>
                </div>
                @error('tanggal_selesai')
                    <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-8">
                <label for="alasan" class="block mb-2 text-sm font-medium text-[#2D2D2D]">Alasan Cuti/Izin</label>
                <textarea name="alasan" id="alasan" rows="5" class="block p-3 w-full text-sm text-[#2D2D2D] bg-white rounded-lg border border-[#C5A880] focus:ring-[#D4AF37] focus:border-[#D4AF37] transition duration-150" placeholder="Tuliskan alasan pengajuan..." required>{{ old('alasan') }}</textarea>
                @error('alasan')
                    <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="text-white bg-[#D4AF37] hover:bg-[#A9892B] font-medium rounded-lg text-sm px-6 py-3 text-center transition duration-200">
                    Ajukan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');

        tanggalMulai.addEventListener('change', function() {
            // Saat tanggal mulai diubah, set nilai minimal tanggal selesai
            // sama dengan tanggal mulai
            if (this.value) {
                tanggalSelesai.min = this.value;
                
                // Kalau tanggal selesai yang udah kepilih ternyata lebih kecil
                // dari tanggal mulai yang baru, reset value-nya
                if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                    tanggalSelesai.value = this.value;
                }
            }
        });
    });
</script>
</x-app-layout>