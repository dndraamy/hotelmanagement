@extends('layouts.inventory')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- HEADER -->
    <div class="flex items-center justify-between border-b border-stone-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-hotel-dark">
                Daftar Pegawai
            </h1>
            <p class="text-sm text-stone-500 mt-1">
                Kelola data pegawai yang terdaftar dalam sistem.
            </p>
        </div>
        <div>
            <a href="{{ route('pegawai.create') }}" class="inline-flex items-center gap-2 bg-hotel-dark hover:bg-black text-hotel-gold px-4 py-2 rounded-xl font-medium transition text-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Pegawai
            </a>
        </div>
    </div>

    <!-- DATA PEGAWAI -->
    <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-sm text-stone-600">
                        <th class="p-4 font-semibold">Nama Lengkap</th>
                        <th class="p-4 font-semibold">Kontak</th>
                        <th class="p-4 font-semibold">Divisi</th>
                        <th class="p-4 font-semibold">Jabatan</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($pegawais as $p)
                        <tr class="border-b border-stone-100 hover:bg-stone-50 transition">
                            <td class="p-4 font-medium text-stone-800">{{ $p->nama_lengkap }}</td>
                            <td class="p-4 text-stone-600">{{ $p->kontak }}</td>
                            <td class="p-4 text-stone-600">{{ $p->divisi->nama_divisi ?? '-' }}</td>
                            <td class="p-4 text-stone-600">{{ $p->jabatan->nama_jabatan ?? '-' }}</td>
                            <td class="p-4 flex items-center justify-center gap-2">
                                <a href="{{ route('pegawai.edit', $p->id_pegawai) }}" class="p-2 text-stone-500 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('pegawai.destroy', $p->id_pegawai) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-stone-500 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-stone-500">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        console.log("Success: {{ session('success') }}");
    </script>
@endif
@endsection
