<x-app-layout>
    <div class="py-8 px-4 sm:px-8" style="background:#FAF9F6; min-height:100vh; font-family:'Montserrat',sans-serif;">
        <div class="bg-white rounded-2xl shadow-sm p-6 max-w-lg mx-auto">

            <a href="{{ route('hrd.jadwal-shift.index') }}"
               class="inline-flex items-center gap-1 text-sm mb-5 transition"
               style="color:#888;">
                ← Kembali
            </a>

            <h1 class="text-xl font-bold mb-1" style="color:#1A1A1A;">Tambah Jadwal Shift</h1>
            <p class="text-sm mb-6" style="color:#888;">Isi form berikut untuk menambahkan jadwal shift pegawai</p>
            <hr style="border-color:#f0ede6;" class="mb-6">

            <form method="POST" action="{{ route('hrd.jadwal-shift.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:#555;">Pegawai</label>
                    <select name="id_pegawai"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none transition"
                            style="border-color:{{ $errors->has('id_pegawai') ? '#e53e3e' : '#e2e0d8' }}; background:#FAF9F6; color:#2D2D2D;">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}" {{ old('id_pegawai') == $p->id_pegawai ? 'selected' : '' }}>
                                {{ $p->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_pegawai')
                        <p class="text-xs mt-1" style="color:#e53e3e;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:#555;">Shift</label>
                    <select name="id_shift"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none"
                            style="border-color:{{ $errors->has('id_shift') ? '#e53e3e' : '#e2e0d8' }}; background:#FAF9F6; color:#2D2D2D;">
                        <option value="">-- Pilih Shift --</option>
                        @foreach($shifts as $s)
                            <option value="{{ $s->id_shift }}" {{ old('id_shift') == $s->id_shift ? 'selected' : '' }}>
                                {{ $s->nama_shift }} ({{ substr($s->jam_mulai,0,5) }} – {{ substr($s->jam_selesai,0,5) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_shift')
                        <p class="text-xs mt-1" style="color:#e53e3e;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:#555;">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}"
                           class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none"
                           style="border-color:{{ $errors->has('tanggal') ? '#e53e3e' : '#e2e0d8' }}; background:#FAF9F6; color:#2D2D2D;">
                    @error('tanggal')
                        <p class="text-xs mt-1" style="color:#e53e3e;">{{ $message }}</p>
                    @enderror
                </div>

                <hr style="border-color:#f0ede6;">
                <button type="submit"
                        class="w-full py-2.5 rounded-lg font-semibold text-sm transition"
                        style="background:#D4AF37; color:#1A1A1A;">
                    Simpan Jadwal
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
