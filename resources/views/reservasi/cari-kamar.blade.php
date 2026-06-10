<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Cari Kamar Tersedia</h1>
        
        <form method="POST" action="{{ route('reservasi.proses-cari') }}" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Tanggal Check-in</label>
                <input type="date" name="tanggal_checkin" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Tanggal Check-out</label>
                <input type="date" name="tanggal_checkout" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Tipe Kamar</label>
                <select name="tipe_kamar_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">Pilih Tipe Kamar</option>
                    @foreach($tipeKamar as $tipe)
                        <option value="{{ $tipe->id }}">{{ $tipe->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-lg">
                Cari Kamar
            </button>
        </form>
    </div>
</x-app-layout>
