@extends('layouts.reservasi')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-hotel-text">Pembayaran DP</h1>
    <p class="text-sm text-stone-500 mt-1">Kelola pembayaran DP untuk reservasi</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- FORM PEMBAYARAN DP --}}
    <div class="lg:col-span-2">
        {{-- GUARD: Jika DP sudah tercatat --}}
        @if(isset($dpSudahDibayar) && $dpSudahDibayar)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-emerald-700 text-lg">DP Sudah Dibayar</h3>
                    <p class="text-sm text-emerald-600">Pembayaran DP untuk reservasi ini sudah tercatat</p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-emerald-100">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-stone-400">Total DP Diterima</p>
                        <p class="font-bold text-hotel-text">Rp {{ number_format($totalDPDiterima ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-stone-400">Total Tagihan</p>
                        <p class="font-bold text-hotel-text">Rp {{ number_format($reservasi->total_tagihan ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-stone-400">Sisa Tagihan</p>
                        <p class="font-bold text-hotel-gold">Rp {{ number_format($sisaTagihan ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-stone-400">Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Lunas</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex gap-3">
                <a href="{{ route('reservasi.show', $reservasi->id_reservasi) }}"
                    class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-sm font-medium rounded-xl transition">
                    <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i> Lihat Detail
                </a>
                <a href="{{ route('reservasi.index') }}"
                    class="px-4 py-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold rounded-xl transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
        @else
        {{-- FORM PEMBAYARAN --}}
        <div class="bg-white rounded-2xl border border-stone-200 p-6">
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-stone-100">
                <i data-lucide="credit-card" class="w-5 h-5 text-hotel-gold"></i>
                <span class="font-semibold text-hotel-text">Form Pembayaran DP</span>
                <span class="ml-auto text-xs text-stone-400">Reservasi #{{ $reservasi->id_reservasi ?? '' }}</span>
            </div>

            <form action="{{ route('reservasi.simpan-dp', $reservasi->id_reservasi ?? 0) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Informasi Reservasi --}}
                <div class="bg-stone-50 rounded-xl p-4 mb-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-stone-400">Nama Tamu</p>
                            <p class="font-medium">{{ $reservasi->tamu->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-400">Kamar</p>
                            <p class="font-medium">{{ $reservasi->detailKamar->first()->kamar->nomor_kamar ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-400">Check-in / Check-out</p>
                            <p class="font-medium">{{ $reservasi->tgl_checkin ?? '-' }} / {{ $reservasi->tgl_checkout ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-stone-400">Status</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu DP</span>
                        </div>
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">
                            Nominal DP <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">Rp</span>
                            <input type="number"
                                name="nominal"
                                id="nominalDp"
                                value="{{ old('nominal', ($reservasi->total_tagihan ?? 0) * 0.5) }}"
                                required
                                min="1"
                                class="w-full border border-stone-300 rounded-xl px-3 py-2 pl-10 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                        </div>
                        @error('nominal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode_bayar" required
                            class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                            <option value="">Pilih Metode</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Kartu Kredit">Kartu Kredit</option>
                            <option value="Debit">Debit</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                        </select>
                        @error('metode_bayar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">
                            Tanggal Terima <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                            name="tanggal_terima"
                            value="{{ old('tanggal_terima', date('Y-m-d')) }}"
                            required
                            class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                        @error('tanggal_terima') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">
                            Nomor Referensi
                        </label>
                        <input type="text"
                            name="nomor_referensi"
                            value="{{ old('nomor_referensi') }}"
                            placeholder="Contoh: TRX-2026-001"
                            class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                        @error('nomor_referensi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">
                            Upload Bukti Pembayaran
                        </label>
                        <div class="mt-1 border-2 border-dashed border-stone-300 rounded-xl p-6 text-center hover:border-hotel-gold transition cursor-pointer"
                            id="dropZone">
                            <input type="file"
                                name="bukti_pembayaran"
                                id="buktiPembayaran"
                                accept=".jpg,.jpeg,.png,.pdf"
                                class="hidden">
                            <i data-lucide="upload-cloud" class="w-10 h-10 text-stone-300 mx-auto"></i>
                            <p class="text-sm text-stone-400 mt-2">
                                <span class="text-hotel-gold font-semibold">Klik untuk upload</span> atau drag & drop
                            </p>
                            <p class="text-xs text-stone-300 mt-1">JPG, PNG, PDF (Max 2MB)</p>
                            <div id="filePreview" class="hidden mt-3">
                                <div class="flex items-center justify-between bg-stone-50 rounded-lg p-3">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="file" class="w-4 h-4 text-hotel-gold"></i>
                                        <span id="fileName" class="text-sm text-stone-600"></span>
                                    </div>
                                    <button type="button" id="removeFile" class="text-red-400 hover:text-red-600">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('bukti_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tombol Shortcut --}}
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs text-stone-500 mr-2">Shortcut:</span>
                    <button type="button" class="shortcut-btn px-3 py-1 bg-stone-100 hover:bg-hotel-gold/20 text-stone-600 text-xs font-medium rounded-lg transition" data-persen="20">
                        20%
                    </button>
                    <button type="button" class="shortcut-btn px-3 py-1 bg-stone-100 hover:bg-hotel-gold/20 text-stone-600 text-xs font-medium rounded-lg transition" data-persen="30">
                        30%
                    </button>
                    <button type="button" class="shortcut-btn px-3 py-1 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-xs font-bold rounded-lg transition" data-persen="50">
                        50% (Minimal)
                    </button>
                    <button type="button" class="shortcut-btn px-3 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-medium rounded-lg transition" data-persen="100">
                        100% (Lunas)
                    </button>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-stone-100">
                    <a href="{{ route('reservasi.index') }}"
                        class="px-4 py-2 border border-stone-300 rounded-xl text-sm hover:bg-stone-50 transition">
                        Lewati
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold rounded-xl transition">
                        <i data-lucide="credit-card" class="w-4 h-4 inline mr-1"></i>
                        Catat Pembayaran
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

    {{-- SIDEBAR --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-stone-200 p-6 sticky top-4">
            <h4 class="font-semibold text-hotel-text mb-4">Breakdown Tagihan</h4>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-500">Biaya Kamar</span>
                    <span>Rp {{ number_format($reservasi->biaya_kamar ?? 0, 0, ',', '.') }}</span>
                </div>

                @php
                $jumlahMalam = 0;
                if (isset($reservasi->tgl_checkin) && isset($reservasi->tgl_checkout)) {
                $jumlahMalam = (int) date_diff(
                date_create($reservasi->tgl_checkin),
                date_create($reservasi->tgl_checkout)
                )->days;
                }
                @endphp
                <div class="flex justify-between text-xs text-stone-400">
                    <span>{{ $jumlahMalam }} malam x Rp {{ number_format(($reservasi->biaya_kamar ?? 0) / max($jumlahMalam, 1), 0, ',', '.') }}</span>
                    <span></span>
                </div>

                <div class="flex justify-between">
                    <span class="text-stone-500">Biaya Tambahan</span>
                    <span>Rp 0</span>
                </div>

                <div class="border-t border-stone-200 pt-3 mt-3">
                    <div class="flex justify-between text-base font-bold">
                        <span>Total Tagihan</span>
                        <span class="text-hotel-text">Rp {{ number_format($reservasi->total_tagihan ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- DP Diterima --}}
                <div class="bg-emerald-50 rounded-xl p-3 mt-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-emerald-700">DP Diterima</span>
                        <span class="font-bold text-emerald-700">Rp {{ number_format($totalDPDiterima ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Sisa Tagihan --}}
                <div class="bg-yellow-50 rounded-xl p-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-yellow-700">Sisa Tagihan</span>
                        <span class="font-bold text-yellow-700">Rp {{ number_format($sisaTagihan ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-yellow-600 mt-1">*DP minimal 50% dari total tagihan</p>
                </div>
            </div>

            {{-- Informasi Tambahan --}}
            <div class="mt-4 p-3 bg-stone-50 rounded-xl border border-stone-200">
                <p class="text-xs text-stone-500">
                    <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                    Reservasi akan otomatis berstatus <strong>"Confirmed"</strong> setelah DP minimal 50% tercatat.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // === SHORTCUT BUTTONS ===
        var shortcutBtns = document.querySelectorAll('.shortcut-btn');
        var nominalInput = document.getElementById('nominalDp');
        var totalTagihan = parseInt('{{ $reservasi->total_tagihan ?? 0 }}');

        if (shortcutBtns.length > 0 && nominalInput) {
            shortcutBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var persen = parseFloat(this.dataset.persen);
                    var nominal = (totalTagihan * persen) / 100;
                    nominalInput.value = Math.round(nominal);

                    shortcutBtns.forEach(function(b) {
                        b.classList.remove('ring-2', 'ring-hotel-gold');
                    });
                    this.classList.add('ring-2', 'ring-hotel-gold');
                });
            });
        }

        // === DRAG & DROP UPLOAD ===
        var dropZone = document.getElementById('dropZone');
        var fileInput = document.getElementById('buktiPembayaran');
        var filePreview = document.getElementById('filePreview');
        var fileName = document.getElementById('fileName');
        var removeFile = document.getElementById('removeFile');

        if (dropZone && fileInput) {
            dropZone.addEventListener('click', function(e) {
                if (!e.target.closest('#removeFile')) {
                    fileInput.click();
                }
            });

            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-hotel-gold', 'bg-hotel-gold/5');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-hotel-gold', 'bg-hotel-gold/5');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-hotel-gold', 'bg-hotel-gold/5');

                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    handleFile(e.dataTransfer.files[0]);
                }
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    handleFile(this.files[0]);
                }
            });
        }

        function handleFile(file) {
            var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            var maxSize = 2 * 1024 * 1024;

            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                if (fileInput) fileInput.value = '';
                return;
            }

            if (file.size > maxSize) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                if (fileInput) fileInput.value = '';
                return;
            }

            if (fileName) fileName.textContent = file.name;
            if (filePreview) filePreview.classList.remove('hidden');

            if (dropZone) {
                var textElement = dropZone.querySelector('p.text-sm.text-stone-400');
                if (textElement) {
                    textElement.classList.add('hidden');
                }
            }
        }

        if (removeFile) {
            removeFile.addEventListener('click', function(e) {
                e.stopPropagation();
                if (fileInput) fileInput.value = '';
                if (filePreview) filePreview.classList.add('hidden');
                if (dropZone) {
                    var textElement = dropZone.querySelector('p.text-sm.text-stone-400');
                    if (textElement) {
                        textElement.classList.remove('hidden');
                    }
                }
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    #dropZone {
        transition: all 0.3s ease;
    }

    #dropZone:hover {
        border-color: #C9A84C;
        background-color: rgba(201, 168, 76, 0.05);
    }

    .shortcut-btn {
        transition: all 0.3s ease;
    }

    .shortcut-btn:hover {
        transform: translateY(-1px);
    }
</style>
@endpush