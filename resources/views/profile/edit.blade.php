@extends('layouts.reservasi')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-hotel-text">Edit Reservasi</h1>
    <p class="text-sm text-stone-500 mt-1">Perbarui data reservasi #{{ $reservasi->id_reservasi }}</p>
</div>

<div class="bg-white rounded-2xl border border-stone-200 p-6">
    <form action="{{ route('reservasi.update', $reservasi->id_reservasi) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Nama Tamu</label>
                <select name="id_tamu" class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @foreach($tamu as $t)
                    <option value="{{ $t->id_tamu }}" {{ $reservasi->id_tamu == $t->id_tamu ? 'selected' : '' }}>
                        {{ $t->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
                @error('id_tamu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Kamar</label>
                <select name="id_kamar" class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    @foreach($kamar as $k)
                    <option value="{{ $k->id_kamar }}" {{ ($reservasi->detailKamar->first()->id_kamar ?? '') == $k->id_kamar ? 'selected' : '' }}>
                        {{ $k->nomor_kamar }} - {{ $k->tipeKamar->nama_tipe }} ({{ $k->status_kamar }})
                    </option>
                    @endforeach
                </select>
                @error('id_kamar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Check-in</label>
                <input type="date" name="tgl_checkin" value="{{ $reservasi->tgl_checkin }}"
                    class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                @error('tgl_checkin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Check-out</label>
                <input type="date" name="tgl_checkout" value="{{ $reservasi->tgl_checkout }}"
                    class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                @error('tgl_checkout') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Status</label>
                <select name="status_reservasi" class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">
                    <option value="Menunggu DP" {{ $reservasi->status_reservasi == 'Menunggu DP' ? 'selected' : '' }}>Menunggu DP</option>
                    <option value="Confirmed" {{ $reservasi->status_reservasi == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="Checked-In" {{ $reservasi->status_reservasi == 'Checked-In' ? 'selected' : '' }}>Checked-In</option>
                    <option value="Checked-Out" {{ $reservasi->status_reservasi == 'Checked-Out' ? 'selected' : '' }}>Checked-Out</option>
                    <option value="Cancelled" {{ $reservasi->status_reservasi == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status_reservasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Catatan</label>
                <textarea name="catatan" rows="3"
                    class="mt-1 w-full border border-stone-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-hotel-gold focus:border-hotel-gold">{{ old('catatan', $reservasi->catatan ?? '') }}</textarea>
                @error('catatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('reservasi.index') }}"
                class="px-4 py-2 border border-stone-300 rounded-xl text-sm hover:bg-stone-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-4 py-2 bg-hotel-gold hover:bg-hotel-goldLight text-hotel-dark text-sm font-bold rounded-xl transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection