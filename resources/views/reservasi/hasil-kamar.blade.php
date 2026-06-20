<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Hasil Pencarian Kamar</h1>

        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            <strong>Check-in:</strong> {{ $request->tanggal_checkin }} |
            <strong>Check-out:</strong> {{ $request->tanggal_checkout }}
        </div>

        @if($kamarTersedia->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Kamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Kamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($kamarTersedia as $kamar)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $kamar->nomor_kamar }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $kamar->tipeKamar->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    {{ $kamar->status_kamar }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('reservasi.buat', ['kamar_id' => $kamar->id, 'tanggal_checkin' => $request->tanggal_checkin, 'tanggal_checkout' => $request->tanggal_checkout]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Pesan
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                Tidak ada kamar tersedia untuk kriteria yang dipilih.
            </div>
        @endif
    </div>
</x-app-layout>
