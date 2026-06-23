@extends('layouts.manajer')

@section('content')
<style>
    .wrap { padding:0; font-family:'Montserrat',sans-serif; color:#2D2D2D; }
    .card { background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:1.5rem; margin-bottom:1.5rem; }
    .page-title { font-size:1.5rem; font-weight:700; color:#1A1A1A; }
    .page-sub { font-size:0.85rem; color:#888; margin-bottom:1.5rem; }
    .filter-bar { display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end; margin-bottom:1.5rem; }
    .filter-label { font-size:0.78rem; font-weight:600; color:#888; margin-bottom:0.25rem; display:block; text-transform:uppercase; letter-spacing:0.04em; }
    .filter-bar select { border:1.5px solid #e2e0d8; border-radius:8px; padding:0.45rem 0.85rem; font-family:'Montserrat',sans-serif; font-size:0.85rem; color:#2D2D2D; background:#FAF9F6; }
    .btn-gold { background:#D4AF37; color:#1A1A1A; font-weight:600; padding:0.5rem 1.25rem; border-radius:8px; border:none; cursor:pointer; font-size:0.9rem; font-family:'Montserrat',sans-serif; }
    .summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
    .summary-card { border-radius:12px; padding:1.25rem 1.5rem; }
    .summary-card.green { background:#f0fdf4; border:1px solid #86efac; }
    .summary-card.red { background:#fff5f5; border:1px solid #feb2b2; }
    .summary-card.blue { background:#eff6ff; border:1px solid #93c5fd; }
    .summary-label { font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem; }
    .summary-card.green .summary-label { color:#166534; }
    .summary-card.red .summary-label { color:#991b1b; }
    .summary-card.blue .summary-label { color:#1e40af; }
    .summary-value { font-size:1.3rem; font-weight:700; }
    .summary-card.green .summary-value { color:#15803d; }
    .summary-card.red .summary-value { color:#dc2626; }
    .summary-card.blue .summary-value { color:#2563eb; }
    .section-title { font-size:1rem; font-weight:700; color:#1A1A1A; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:2px solid #D4AF37; display:inline-block; }
    table { width:100%; border-collapse:collapse; font-size:0.88rem; }
    thead tr { background:#1A1A1A; color:#D4AF37; }
    thead th { padding:0.75rem 1rem; text-align:left; font-size:0.78rem; text-transform:uppercase; letter-spacing:0.05em; }
    tbody tr { border-bottom:1px solid #f0ede6; }
    tbody tr:hover { background:#fdf8ef; }
    tbody td { padding:0.75rem 1rem; }
    .text-right { text-align:right; }
    .badge-in { background:#f0fdf4; color:#166534; border-radius:20px; padding:0.2rem 0.75rem; font-size:0.78rem; font-weight:600; }
    .badge-out { background:#fff5f5; color:#991b1b; border-radius:20px; padding:0.2rem 0.75rem; font-size:0.78rem; font-weight:600; }
    .empty { text-align:center; color:#aaa; padding:2rem; font-size:0.88rem; }
</style>

    <div class="wrap">
        <div class="page-title">Laporan Keuangan Hotel</div>
        <div class="page-sub">Ringkasan pendapatan dan pengeluaran hotel per periode</div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('manajer.laporan-keuangan.index') }}">
            <div class="filter-bar">
                <div>
                    <span class="filter-label">Bulan</span>
                    <select name="bulan">
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="filter-label">Tahun</span>
                    <select name="tahun">
                        @foreach([now()->year-1, now()->year, now()->year+1] as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="padding-top:1.3rem;">
                    <button type="submit" class="btn-gold">Tampilkan</button>
                     <a href="{{ route('manajer.laporan-keuangan.export-pdf', ['bulan' => $bulan,'tahun' => $tahun]) }}"
                        class="btn-gold"
                        style="text-decoration:none;">
                           Download Laporan PDF
                     </a>
                </div>
            </div>
        </form>

        {{-- Summary Cards --}}
        <div class="summary-grid">
            <div class="summary-card green">
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card red">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card blue">
                <div class="summary-label">Selisih (Laba/Rugi)</div>
                <div class="summary-value">Rp {{ number_format($selisih, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Tabel Pendapatan --}}
        <div class="card">
            <div class="section-title">Rincian Pendapatan</div>
            @if($pendapatan->isEmpty())
                <div class="empty">Tidak ada data pendapatan untuk periode ini.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kategori</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendapatan as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge-in">{{ $p->kategori }}</span></td>
                            <td class="text-right">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Tabel Pengeluaran --}}
        <div class="card">
            <div class="section-title">Rincian Pengeluaran</div>
            @if($pengeluaran->isEmpty())
                <div class="empty">Tidak ada data pengeluaran untuk periode ini.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kategori</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengeluaran as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge-out">{{ $p->kategori }}</span></td>
                            <td class="text-right">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Ringkasan Tahunan --}}
        <div class="card">
            <div class="section-title">Ringkasan Per Bulan ({{ $tahun }})</div>
            @if($ringkasanTahunan->isEmpty())
                <div class="empty">Tidak ada data untuk tahun ini.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th class="text-right">Pendapatan</th>
                            <th class="text-right">Pengeluaran</th>
                            <th class="text-right">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ringkasanTahunan as $r)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $r->bulan)->format('F') }}</td>
                            <td class="text-right" style="color:#15803d;">Rp {{ number_format($r->total_pendapatan, 0, ',', '.') }}</td>
                            <td class="text-right" style="color:#dc2626;">Rp {{ number_format($r->total_pengeluaran, 0, ',', '.') }}</td>
                            <td class="text-right" style="color:{{ $r->selisih >= 0 ? '#2563eb' : '#dc2626' }}; font-weight:600;">
                                Rp {{ number_format($r->selisih, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
