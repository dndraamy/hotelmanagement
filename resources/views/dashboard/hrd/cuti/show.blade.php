@extends('layouts.hrd')
@section('content')

{{-- Back --}}
<div>
    <a href="{{ route('hrd.dashboard.hrd.cuti.index') }}"
        class="text-xs text-stone-500 hover:text-hotel-dark flex items-center gap-1 w-fit">
        <i data-lucide="arrow-left" class="w-3 h-3"></i> Kembali ke Daftar
    </a>
</div>

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    {{-- Card header --}}
    <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
        <h2 class="font-bold text-hotel-dark">Detail Pengajuan Cuti</h2>
        @php
        $badge = match($pengajuanCuti->status_approval) {
        'Approved' => 'bg-emerald-100 text-emerald-700',
        'Rejected' => 'bg-red-100 text-red-700',
        default => 'bg-yellow-100 text-yellow-700',
        };
        @endphp
        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badge }}">
            {{ $pengajuanCuti->status_approval }}
        </span>
    </div>

    <div class="p-6 space-y-6">

        {{-- Data karyawan --}}
        <div>
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-3">
                Data Karyawan
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] text-stone-400 mb-0.5">Nama Lengkap</p>
                    <p class="text-sm font-semibold text-hotel-dark">
                        {{ $pengajuanCuti->pegawai->nama_lengkap }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-stone-400 mb-0.5">Kontak</p>
                    <p class="text-sm text-stone-600">
                        {{ $pengajuanCuti->pegawai->kontak ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <hr class="border-stone-100">

        {{-- Rincian pengajuan --}}
        <div>
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-3">
                Rincian Pengajuan
            </p>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-[10px] text-stone-400 mb-0.5">Tanggal Mulai</p>
                    <p class="text-sm font-semibold text-hotel-dark">
                        {{ $pengajuanCuti->tanggal_mulai->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-stone-400 mb-0.5">Tanggal Selesai</p>
                    <p class="text-sm font-semibold text-hotel-dark">
                        {{ $pengajuanCuti->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-stone-400 mb-0.5">Durasi</p>
                    <span class="text-xs font-semibold bg-stone-100 text-stone-600 px-2 py-1 rounded-full">
                        {{ $pengajuanCuti->durasi_hari }} hari
                    </span>
                </div>
            </div>
            <div>
                <p class="text-[10px] text-stone-400 mb-0.5">Alasan</p>
                <p class="text-sm text-stone-600">{{ $pengajuanCuti->alasan }}</p>
            </div>
        </div>

        {{-- Info approver --}}
        @if ($pengajuanCuti->status_approval !== 'Pending' && $pengajuanCuti->id_approver)
        <hr class="border-stone-100">
        <div>
            <p class="text-[10px] uppercase tracking-widest text-stone-400 font-semibold mb-3">
                Diproses Oleh
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] text-stone-400 mb-0.5">Nama HRD</p>
                    <p class="text-sm font-semibold text-hotel-dark">
                        @php
                        // Cari User berdasarkan id_approver
                        $userApprover = \App\Models\User::find($pengajuanCuti->id_approver);
                        // Cari Pegawai berdasarkan id_pegawai milik user tersebut
                        $pegawai = $userApprover ? \App\Models\Pegawai::find($userApprover->id_pegawai) : null;
                        @endphp
                        {{ $pegawai->nama_lengkap ?? 'Data tidak ditemukan' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-stone-400 mb-0.5">Tanggal Diproses</p>
                    <p class="text-sm text-stone-600">
                        {{ $pengajuanCuti->updated_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Tombol aksi --}}
    @if ($pengajuanCuti->status_approval === 'Pending')
    <div class="px-6 py-4 border-t border-stone-100 bg-stone-50 flex justify-end gap-3">
        <button onclick="document.getElementById('modalTolak').classList.remove('hidden')"
            class="px-4 py-2 text-xs font-semibold rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition">
            Tolak Pengajuan
        </button>
        <button onclick="document.getElementById('modalSetuju').classList.remove('hidden')"
            class="px-4 py-2 text-xs font-semibold rounded-xl bg-hotel-gold text-hotel-dark hover:opacity-90 transition">
            Setujui Pengajuan
        </button>
    </div>
    @endif
</div>

{{-- Modal Setuju --}}
<div id="modalSetuju" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <form method="POST" action="{{ route('hrd.dashboard.hrd.cuti.approve', $pengajuanCuti->id_cuti) }}">
            @csrf @method('PATCH')
            <div class="p-6 border-b border-stone-100">
                <h3 class="font-bold text-hotel-dark">Konfirmasi Persetujuan</h3>
                <p class="text-xs text-stone-500 mt-1">Setujui pengajuan cuti dari <strong>{{ $pengajuanCuti->pegawai->nama_lengkap }}</strong>?</p>
            </div>
            <div class="p-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalSetuju').classList.add('hidden')"
                    class="px-4 py-2 text-xs font-semibold rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-500 text-white hover:bg-emerald-600 transition">Ya, Setujui</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak --}}
<div id="modalTolak" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <form method="POST" action="{{ route('hrd.dashboard.hrd.cuti.reject', $pengajuanCuti->id_cuti) }}">
            @csrf @method('PATCH')
            <div class="p-6 border-b border-stone-100">
                <h3 class="font-bold text-hotel-dark">Konfirmasi Penolakan</h3>
                <p class="text-xs text-stone-500 mt-1">Tolak pengajuan cuti dari <strong>{{ $pengajuanCuti->pegawai->nama_lengkap }}</strong>?</p>
            </div>
            <div class="p-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalTolak').classList.add('hidden')"
                    class="px-4 py-2 text-xs font-semibold rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl bg-red-500 text-white hover:bg-red-600 transition">Ya, Tolak</button>
            </div>
        </form>
    </div>
</div>

@endsection