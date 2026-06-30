@extends('layouts.hrd')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard HRD</h1>
        <p class="text-stone-500">Pilih menu di bawah ini untuk mengelola data kepegawaian.</p>
    </div>

    {{-- Quick Access Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <a href="{{ url('/hrd/dashboard/hrd/pegawai') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-100 hover:border-amber-400 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-100 transition">
                <i data-lucide="users" class="w-6 h-6 text-amber-600"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800">Kelola Pegawai</h3>
            <p class="text-sm text-stone-500 mt-1">Tambah, edit, atau hapus data pegawai hotel.</p>
        </a>

        <a href="{{ url('/hrd/dashboard/hrd/jadwal-shift') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-100 hover:border-blue-400 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-100 transition">
                <i data-lucide="calendar-days" class="w-6 h-6 text-blue-600"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800">Jadwal Shift</h3>
            <p class="text-sm text-stone-500 mt-1">Atur jadwal kerja dan shift karyawan.</p>
        </a>

        <a href="{{ url('/hrd/dashboard/hrd/cuti') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-100 hover:border-rose-400 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-rose-100 transition">
                <i data-lucide="calendar-off" class="w-6 h-6 text-rose-600"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800">Data Cuti & Izin</h3>
            <p class="text-sm text-stone-500 mt-1">Pantau pengajuan cuti dan izin pegawai.</p>
        </a>

    </div>

</div>

{{-- Script Ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection