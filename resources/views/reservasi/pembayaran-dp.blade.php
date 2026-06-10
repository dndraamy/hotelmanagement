<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Pembayaran DP Reservasi</h1>
        
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <p><strong>Tamu:</strong> {{ $reservasi->tamu->nama }}</p>
            <p><strong>Kamar:</strong> {{ $reservasi->detailKamar->first()->kamar->nomor_kamar }} - {{ $reservasi->detailKamar->first()->kamar->tipeKamar->nama }}</p>
            <p><strong>Check-in:</strong> {{ $reservasi->tanggal_checkin }}</p>
            <p><strong>Check-out:</strong> {{ $reservasi->tanggal_checkout }}</p>
            <p><strong>Total Harga:</strong> Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
        </div>

        <form method="POST" action="{{ route('reservasi.simpan-dp', $reservasi->id) }}" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Jumlah DP (minimal 30% dari total)</label>
                <input type="number" name="jumlah_dp" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                       min="{{ $totalHarga * 0.3 }}" max="{{ $totalHarga }}" required>
                <small class="text-gray-500">Minimal: Rp {{ number_format($totalHarga * 0.3, 0, ',', '.') }}</small>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-lg">
                Bayar DP
            </button>
        </form>
    </div>
</x-app-layout>
