<x-app-layout>
    <div class="min-h-screen bg-[#FAF9F6] py-12 font-['Montserrat']">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl border border-white/40 overflow-hidden" 
                 x-data="{ tab: 'Pemasukan' }"> <div class="border-b border-gray-200">
                    <div class="flex">
                        <button @click="tab = 'Pemasukan'" 
                                :class="tab === 'Pemasukan' ? 'border-[#D4AF37] text-[#D4AF37]' : 'border-transparent text-[#2D2D2D] hover:text-[#C5A880]'"
                                class="flex-1 py-4 text-center border-b-4 font-bold text-lg transition-colors duration-300">
                            💰 Uang Masuk (Pemasukan)
                        </button>
                        <button @click="tab = 'Pengeluaran'" 
                                :class="tab === 'Pengeluaran' ? 'border-[#D4AF37] text-[#D4AF37]' : 'border-transparent text-[#2D2D2D] hover:text-[#C5A880]'"
                                class="flex-1 py-4 text-center border-b-4 font-bold text-lg transition-colors duration-300">
                            💸 Uang Keluar (Belanja)
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="tipe_transaksi" x-bind:value="tab">

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-[#2D2D2D]">Kategori Transaksi</label>
                                <select name="kategori" class="w-full mt-2 p-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-[#D4AF37] focus:border-[#D4AF37] transition" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <optgroup x-show="tab === 'Pemasukan'" label="Pemasukan">
                                        <option value="Pembayaran Kamar">Pembayaran Kamar</option>
                                        <option value="POS Restoran">Pendapatan POS Restoran</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </optgroup>
                                    <optgroup x-show="tab === 'Pengeluaran'" label="Pengeluaran">
                                        <option value="Operasional">Belanja Operasional</option>
                                        <option value="Gaji">Pembayaran Gaji</option>
                                        <option value="Perbaikan">Maintenance/Perbaikan</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-[#2D2D2D]">Nominal (Rp)</label>
                                    <input type="number" name="nominal" placeholder="Contoh: 1500000" class="w-full mt-2 p-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-[#D4AF37] focus:border-[#D4AF37] transition" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-[#2D2D2D]">Tanggal Transaksi</label>
                                    <input type="date" name="tanggal_transaksi" class="w-full mt-2 p-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-[#D4AF37] focus:border-[#D4AF37] transition" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-[#2D2D2D]">Keterangan Detail</label>
                                <textarea name="keterangan" rows="3" placeholder="Tuliskan rincian transaksi di sini..." class="w-full mt-2 p-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-[#D4AF37] focus:border-[#D4AF37] transition" required></textarea>
                            </div>

                            <div x-show="tab === 'Pengeluaran'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="p-4 bg-[#FAF9F6] border border-dashed border-[#C5A880] rounded-xl">
                                <label class="block text-sm font-semibold text-[#2D2D2D] mb-2">📸 Unggah Bukti Nota (Opsional/Wajib untuk Belanja)</label>
                                <input type="file" name="bukti_nota" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#D4AF37] file:text-white hover:file:bg-[#C5A880] transition">
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="w-full py-4 bg-[#D4AF37] hover:bg-[#C5A880] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    Simpan Transaksi <span x-text="tab"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>