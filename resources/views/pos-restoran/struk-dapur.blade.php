<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan #{{ str_pad($pesanan->id_pesanan, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 10px; /* Margin untuk kertas PDF */
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px; /* Dikecilkan agar pas di kertas 80mm */
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .header {
            margin-bottom: 6px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-info td {
            font-size: 11px;
            padding: 1px 0;
            vertical-align: top;
        }
        .items {
            margin-top: 2px;
        }
        .items td {
            font-size: 11px;
            padding: 2px 0;
            vertical-align: top;
        }
        .item-name {
            display: block;
            margin-bottom: 2px;
        }
        .totals td {
            font-size: 12px;
            padding: 2px 0;
        }
        .footer {
            margin-top: 10px;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header text-center">
        <h2>Hotel Management</h2>
        <p>Restoran & Cafe</p>
        <p>Jl. Contoh Alamat Hotel No. 123</p>
        <p>0812-3456-7890</p>
    </div>

    <div class="divider"></div>

    <table class="meta-info">
        <tr>
            <td class="text-left" style="width: 40%;">{{ $pesanan->tanggal_pesanan->format('Y-m-d') }}</td>
            <td class="text-right" style="width: 60%;">Petugas: {{ auth()->user()->name ?? 'Restoran' }}</td>
        </tr>
        <tr>
            <td class="text-left">{{ $pesanan->tanggal_pesanan->format('H:i:s') }}</td>
            <td class="text-right">Tamu: {{ $pesanan->reservasi ? ($pesanan->reservasi->tamu?->nama_lengkap ?? 'Unknown') : 'Walk-in' }}</td>
        </tr>
        <tr>
            <td class="text-left">No.{{ str_pad($pesanan->id_pesanan, 4, '0', STR_PAD_LEFT) }}</td>
            <td class="text-right">
                @if($pesanan->reservasi)
                    @php
                        $nomorKamar = $pesanan->reservasi->detailKamar->map(fn($dk) => $dk->kamar?->nomor_kamar)->filter()->implode(', ');
                    @endphp
                    Kamar: {{ $nomorKamar ?: '-' }}
                @endif
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="items">
        @foreach($pesanan->detailPesananRestoran as $detail)
        <tr>
            <td colspan="2">
                <span class="item-name">{{ $detail->itemMenu?->nama_item ?? 'Item Tidak Diketahui' }}</span>
            </td>
        </tr>
        <tr>
            <td class="text-left" style="width: 50%;">
                {{ $detail->qty }} X {{ number_format($detail->itemMenu?->harga ?? 0, 0, ',', '.') }}
            </td>
            <td class="text-right" style="width: 50%;">
                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table class="totals">
        <tr>
            <td class="text-left" style="width: 50%;">Sub Total</td>
            <td class="text-right" style="width: 50%;">{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-left bold">Total</td>
            <td class="text-right bold">{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-left">Status</td>
            <td class="text-right">{{ $pesanan->status_pembayaran }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer text-center">
        <p>Terima Kasih Atas Kunjungan Anda</p>
        <p>Harap disiapkan oleh tim dapur</p>
    </div>

</body>
</html>
