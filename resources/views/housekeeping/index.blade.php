<x-app-layout>

  <x-slot name="header">
    <div>
        <h2 class="text-4xl font-bold text-white">
            Housekeeping Management
        </h2>

        <p class="text-gray-300 mt-2">
            Monitor dan perbarui status kebersihan kamar hotel
        </p>
    </div>
</x-slot>

    <div class="min-h-screen bg-[#FAF9F6] py-8">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white rounded-2xl shadow-md px-5 py-4 border-l-4 border-[#D4AF37]">
                    <p class="text-gray-500 text-sm">
                        Kamar Perlu Dibersihkan
                    </p>

                   <h2 class="text-3xl font-bold text-red-600">
                        {{ $kamarKotor->count() }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow-md px-5 py-4 border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">
                        Status Sistem
                    </p>

                    <h2 class="text-3xl font-bold text-green-600 mt-2">
                        Aktif
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow-md px-5 py-4 border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm">
                        Modul
                    </p>

                    <h2 class="text-3xl font-bold text-[#1A1A1A] mt-2">
                        Housekeeping
                    </h2>
                </div>

            </div>

            @if(session('success'))
                <div
                    style="
                    background:#DCFCE7;
                    color:#166534;
                    padding:12px;
                    border-radius:10px;
                    margin-bottom:20px;
                ">
                    {{ session('success') }}
                </div>
                @endif

            <!-- Tabel -->
            <div class="bg-white rounded-2xl shadow-lg">

                <div style="background:#1A1A1A;" class="px-6 py-5">

                    <h3 class="text-white text-xl font-semibold">
                        Daftar Kamar yang Perlu Dibersihkan
                    </h3>

                    <p class="text-gray-300 text-sm mt-1">
                        Petugas housekeeping dapat memperbarui status kamar setelah dibersihkan
                    </p>

                </div>

                <div class="p-6">

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead>

                                <tr class="hover:bg-gray-50 transition">

                                    <th class="py-4 text-left text-gray-600 uppercase text-sm">
                                        No Kamar
                                    </th>

                                    <th class="py-4 text-left text-gray-600 uppercase text-sm">
                                        Lantai
                                    </th>

                                    <th class="py-4 text-left text-gray-600 uppercase text-sm">
                                        Status
                                    </th>

                                    <th class="py-4 text-center text-gray-600 uppercase text-sm">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($kamarKotor as $kamar)

                                    <tr class="border-b hover:bg-gray-50 transition">

                                        <td class="py-5 font-semibold text-lg">
                                            {{ $kamar->nomor_kamar }}
                                        </td>

                                        <td class="py-5">
                                            Lantai {{ $kamar->lantai }}
                                        </td>

                                        <td class="py-5">

                                           <span
                                                style="
                                                    background:#FEE2E2;
                                                    color:#DC2626;
                                                    padding:6px 12px;
                                                    border-radius:999px;
                                                    font-size:14px;
                                                    font-weight:600;
                                                ">
                                                {{ $kamar->status_kamar }}
                                            </span>

                                        </td>

                                        <td class="py-5 text-center">

                                            <form action="{{ route('housekeeping.bersih', $kamar->id_kamar) }}" method="POST">

                                                @csrf

                                              <button
                                                    type="submit"
                                                    style="
                                                        background:#D4AF37;
                                                        color:white;
                                                        min-width:140px;
                                                        padding:10px 16px;
                                                        border:none;
                                                        border-radius:10px;
                                                        font-weight:600;
                                                        box-shadow:0 4px 12px rgba(0,0,0,.15);
                                                        cursor:pointer;
                                                        transition:all .2s ease;
                                                    "
                                                    onmouseover="this.style.background='#C19B2E';this.style.transform='scale(1.05)'"
                                                    onmouseout="this.style.background='#D4AF37';this.style.transform='scale(1)'"
                                                >
                                                    Tandai Bersih
                                                </button>
                                                </button>
                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="py-20 text-center">

                                           <h3 class="text-xl font-semibold text-green-700">
                                                Tidak Ada Tugas Housekeeping
                                            </h3>

                                            <p class="text-gray-500 mt-2">
                                                Seluruh kamar saat ini berada dalam kondisi bersih dan siap digunakan.
                                            </p>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>