@extends('layouts.inventory')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- HEADER -->
    <div class="flex items-center justify-between border-b border-stone-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-hotel-dark">
                Tambah Pegawai Baru
            </h1>
            <p class="text-sm text-stone-500 mt-1">
                Masukkan detail informasi pegawai baru untuk disimpan ke sistem.
            </p>
        </div>
        <div>
            <a href="{{ route('inventory.index') }}" class="inline-flex items-center gap-2 text-stone-500 hover:text-hotel-dark font-medium transition text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- VALIDATION ERRORS -->
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-sm">
            <div class="flex gap-2 items-center font-bold mb-1">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                <span>Periksa kembali input Anda:</span>
            </div>
            <ul class="list-disc pl-5 space-y-1 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM CARD -->
    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('pegawai.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- NAMA LENGKAP -->
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-2">
                    Nama Lengkap
                </label>
                <input 
                    type="text" 
                    name="nama_lengkap" 
                    value="{{ old('nama_lengkap') }}"
                    placeholder="Contoh: Budi Santoso"
                    class="w-full border border-stone-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition" 
                    required>
            </div>

            <!-- KONTAK -->
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-2">
                    Nomor Kontak / Telepon
                </label>
                <input 
                    type="text" 
                    name="kontak" 
                    value="{{ old('kontak') }}"
                    placeholder="Contoh: 08123456789"
                    class="w-full border border-stone-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition" 
                    required>
            </div>

            <!-- ALAMAT -->
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-2">
                    Alamat Lengkap
                </label>
                <textarea 
                    name="alamat" 
                    rows="3"
                    placeholder="Masukkan alamat lengkap..."
                    class="w-full border border-stone-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition" 
                    required>{{ old('alamat') }}</textarea>
            </div>

            <!-- DIVISI & JABATAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        Divisi
                    </label>
                    <select name="id_divisi" class="w-full border border-stone-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition bg-white" required>
                        <option value="" disabled selected>Pilih Divisi</option>
                        @foreach($divisi as $d)
                            <option value="{{ $d->id_divisi }}" {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        Jabatan
                    </label>
                    <select name="id_jabatan" class="w-full border border-stone-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition bg-white" required>
                        <option value="" disabled selected>Pilih Jabatan</option>
                        @foreach($jabatan as $j)
                            <option value="{{ $j->id_jabatan }}" {{ old('id_jabatan') == $j->id_jabatan ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="pt-4 flex gap-3">
                <button 
                    type="submit" 
                    class="flex-1 bg-hotel-dark hover:bg-black text-hotel-gold font-semibold py-3 rounded-xl transition">
                    Simpan Pegawai
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <script>
        console.log("Success: {{ session('success') }}");
    </script>
@endif
@endsection
