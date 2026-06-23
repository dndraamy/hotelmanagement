@extends('layouts.reservasi')

@section('content')

{{-- BREADCRUMB --}}
<div class="flex items-center gap-2 text-sm text-stone-400">
    <a href="{{ route('checkin.index') }}" class="hover:text-hotel-gold transition">Layanan Resepsionis</a>
    <i data-lucide="chevron-right" class="w-3 h-3"></i>
    <span class="text-hotel-text font-semibold">Form Check-In</span>
</div>

{{-- PAGE HEADER --}}
<div class="flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold text-hotel-text">Form Check-In Tamu</h1>
        <p class="text-sm text-stone-500 mt-1">Verifikasi dan simpan data identitas tamu untuk reservasi
            <span class="font-semibold text-hotel-text">#{{ $reservasi->id_reservasi }}</span>.
        </p>
    </div>
    <a href="{{ route('checkin.index') }}"
       class="flex items-center gap-2 border border-stone-200 hover:bg-stone-100 text-stone-600 text-xs font-semibold px-4 py-2.5 rounded-xl transition">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── LEFT: Reservation Detail Card ─────────────────────────────────── --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Reservasi Info --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-4 h-4 text-hotel-gold"></i>
                <h3 class="font-bold text-sm text-hotel-text">Detail Reservasi</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-1">ID Reservasi</p>
                    <p class="font-mono text-sm font-bold text-hotel-text">#{{ $reservasi->id_reservasi }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-1">Check-In</p>
                        <p class="text-sm font-semibold text-hotel-text">
                            {{ \Carbon\Carbon::parse($reservasi->tgl_checkin)->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-1">Check-Out</p>
                        <p class="text-sm font-semibold text-hotel-text">
                            {{ \Carbon\Carbon::parse($reservasi->tgl_checkout)->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-1">Durasi</p>
                    @php
                        $nights = \Carbon\Carbon::parse($reservasi->tgl_checkin)
                                    ->diffInDays(\Carbon\Carbon::parse($reservasi->tgl_checkout));
                    @endphp
                    <p class="text-sm font-semibold text-hotel-text">{{ $nights }} Malam</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-2">Kamar</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($reservasi->detailKamar as $dk)
                            <div class="flex items-center gap-2 bg-hotel-gold/10 border border-hotel-gold/20 rounded-xl px-3 py-2">
                                <i data-lucide="bed-double" class="w-4 h-4 text-hotel-gold"></i>
                                <div>
                                    <p class="text-xs font-bold text-hotel-text">{{ $dk->kamar->nomor_kamar ?? '—' }}</p>
                                    <p class="text-[10px] text-stone-400">Lantai {{ $dk->kamar->lantai ?? '?' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-stone-400">Tidak ada kamar terkait</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-1">Status Saat Ini</p>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-hotel-gold/10 text-yellow-700">
                        {{ $reservasi->status_reservasi }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="bg-hotel-dark rounded-2xl p-5 text-stone-300">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="info" class="w-4 h-4 text-hotel-gold"></i>
                <p class="text-xs font-bold text-hotel-gold uppercase tracking-wider">Yang Akan Terjadi</p>
            </div>
            <ul class="space-y-2 text-xs text-stone-400">
                <li class="flex items-start gap-2">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400 mt-0.5 shrink-0"></i>
                    Data identitas tamu tersimpan ke database
                </li>
                <li class="flex items-start gap-2">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400 mt-0.5 shrink-0"></i>
                    Status reservasi berubah → <strong class="text-white">Checked-In</strong>
                </li>
                <li class="flex items-start gap-2">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400 mt-0.5 shrink-0"></i>
                    Status kamar berubah → <strong class="text-white">Terisi</strong>
                </li>
            </ul>
        </div>

    </div>

    {{-- ── RIGHT: Identity Form ────────────────────────────────────────────── --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100 flex items-center gap-2">
                <i data-lucide="id-card" class="w-4 h-4 text-hotel-gold"></i>
                <h3 class="font-bold text-sm text-hotel-text">Data Identitas Tamu</h3>
                <span class="ml-auto text-xs text-stone-400">Semua field wajib diisi</span>
            </div>

            <form action="{{ route('checkin.proses', $reservasi->id_reservasi) }}" method="POST" class="p-6 space-y-6" id="form-checkin">
                @csrf

                {{-- Tipe Identitas --}}
                <div>
                    <label class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                        Tipe Identitas <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label id="label-ktp"
                            class="identity-option flex items-center gap-3 border-2 rounded-xl p-4 cursor-pointer transition
                                   {{ old('tipe_identitas', $reservasi->tamu->tipe_identitas) == 'KTP' ? 'border-hotel-gold bg-hotel-gold/5' : 'border-stone-200 hover:border-hotel-gold/50' }}">
                            <input type="radio" name="tipe_identitas" value="KTP" class="sr-only"
                                   {{ old('tipe_identitas', $reservasi->tamu->tipe_identitas) == 'KTP' ? 'checked' : '' }}
                                   onchange="onIdentityChange(this)">
                            <div class="w-10 h-10 rounded-lg bg-hotel-gold/10 flex items-center justify-center shrink-0">
                                <i data-lucide="credit-card" class="w-5 h-5 text-hotel-gold"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-hotel-text">KTP</p>
                                <p class="text-xs text-stone-400">Kartu Tanda Penduduk</p>
                            </div>
                        </label>

                        <label id="label-paspor"
                            class="identity-option flex items-center gap-3 border-2 rounded-xl p-4 cursor-pointer transition
                                   {{ old('tipe_identitas', $reservasi->tamu->tipe_identitas) == 'Paspor' ? 'border-hotel-gold bg-hotel-gold/5' : 'border-stone-200 hover:border-hotel-gold/50' }}">
                            <input type="radio" name="tipe_identitas" value="Paspor" class="sr-only"
                                   {{ old('tipe_identitas', $reservasi->tamu->tipe_identitas) == 'Paspor' ? 'checked' : '' }}
                                   onchange="onIdentityChange(this)">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                <i data-lucide="book-open" class="w-5 h-5 text-blue-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-hotel-text">Paspor</p>
                                <p class="text-xs text-stone-400">Passport / WNA</p>
                            </div>
                        </label>
                    </div>
                    @error('tipe_identitas')
                        <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nomor Identitas --}}
                <div>
                    <label for="no_identitas" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                        Nomor Identitas <span class="text-red-500">*</span>
                        <span id="hint-ktp" class="normal-case font-normal text-stone-400 ml-1">(16 digit untuk KTP)</span>
                    </label>
                    <input type="text"
                           id="no_identitas"
                           name="no_identitas"
                           value="{{ old('no_identitas', $reservasi->tamu->no_identitas) }}"
                           placeholder="Masukkan nomor identitas..."
                           maxlength="50"
                           class="w-full border @error('no_identitas') border-red-400 bg-red-50 @else border-stone-200 @enderror rounded-xl px-4 py-3 text-sm text-hotel-text placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-hotel-gold/40 focus:border-hotel-gold transition font-mono tracking-wider">
                    @error('no_identitas')
                        <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                        </p>
                    @enderror
                    <div id="ktp-counter" class="mt-1 text-right text-xs text-stone-400 hidden">
                        <span id="ktp-digit-count">0</span> / 16 digit
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label for="nama_lengkap" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_lengkap"
                           name="nama_lengkap"
                           value="{{ old('nama_lengkap', $reservasi->tamu->nama_lengkap) }}"
                           placeholder="Nama lengkap sesuai identitas..."
                           maxlength="100"
                           class="w-full border @error('nama_lengkap') border-red-400 bg-red-50 @else border-stone-200 @enderror rounded-xl px-4 py-3 text-sm text-hotel-text placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-hotel-gold/40 focus:border-hotel-gold transition">
                    @error('nama_lengkap')
                        <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kontak --}}
                <div>
                    <label for="kontak" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-2">
                        Nomor Kontak (HP / Email) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="kontak"
                           name="kontak"
                           value="{{ old('kontak', $reservasi->tamu->kontak) }}"
                           placeholder="Contoh: 08123456789 atau tamu@email.com"
                           maxlength="50"
                           class="w-full border @error('kontak') border-red-400 bg-red-50 @else border-stone-200 @enderror rounded-xl px-4 py-3 text-sm text-hotel-text placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-hotel-gold/40 focus:border-hotel-gold transition">
                    @error('kontak')
                        <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 pt-2 border-t border-stone-100">
                    <a href="{{ route('checkin.index') }}"
                       class="flex-1 flex items-center justify-center gap-2 border border-stone-200 hover:bg-stone-50 text-stone-600 text-sm font-semibold px-4 py-3 rounded-xl transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold px-4 py-3 rounded-xl transition shadow-md shadow-hotel-gold/20">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Konfirmasi Check-In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Identity type card highlight
    function onIdentityChange(radio) {
        document.querySelectorAll('.identity-option').forEach(label => {
            label.classList.remove('border-hotel-gold', 'bg-hotel-gold/5');
            label.classList.add('border-stone-200');
        });
        const parentLabel = radio.closest('.identity-option');
        parentLabel.classList.add('border-hotel-gold', 'bg-hotel-gold/5');
        parentLabel.classList.remove('border-stone-200');

        // Show/hide KTP counter hint
        const isKtp = radio.value === 'KTP';
        document.getElementById('hint-ktp').style.display = isKtp ? 'inline' : 'none';
        document.getElementById('ktp-counter').classList.toggle('hidden', !isKtp);
    }

    // KTP digit counter
    document.getElementById('no_identitas').addEventListener('input', function () {
        const ktp = document.querySelector('input[name="tipe_identitas"]:checked');
        if (ktp && ktp.value === 'KTP') {
            const digits = this.value.replace(/\D/g, '').length;
            document.getElementById('ktp-digit-count').textContent = digits;
            const counter = document.getElementById('ktp-counter');
            counter.classList.remove('hidden');
            document.getElementById('ktp-digit-count').style.color = digits === 16 ? '#16a34a' : '#ef4444';
        }
    });

    // Init state on page load
    (function () {
        const checked = document.querySelector('input[name="tipe_identitas"]:checked');
        if (checked) onIdentityChange(checked);
        lucide.createIcons();
    })();
</script>
@endpush

@endsection