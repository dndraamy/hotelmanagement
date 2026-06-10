<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Form Reservasi Kamar</h1>
        
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            <strong>Kamar:</strong> {{ $kamar->nomor_kamar }} - {{ $kamar->tipeKamar->nama }}
        </div>

        <form method="POST" action="{{ route('reservasi.simpan-reservasi') }}" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
            <input type="hidden" name="tanggal_checkin" value="{{ $request->tanggal_checkin }}">
            <input type="hidden" name="tanggal_checkout" value="{{ $request->tanggal_checkout }}">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nama Tamu</label>
                <input type="text" name="nama_tamu" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nomor KTP/Paspor</label>
                <input type="text" name="no_ktp" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nomor HP</label>
                <input type="text" name="no_hp" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-medium px-4 py-2 rounded-lg">
                Simpan Reservasi
            </button>
        </form>
    </div>
</x-app-layout>
