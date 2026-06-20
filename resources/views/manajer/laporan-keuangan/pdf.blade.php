<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Hotel</title>

    <style>
        body {
            font-family: TimesNewRoman, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background: #f2f2f2;
        }

        th, td {
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Laporan Keuangan Hotel</h1>

<p>
    Periode:
    {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
    {{ $tahun }}
</p>

<div class="summary">
    <p><strong>Total Pendapatan:</strong>
        Rp {{ number_format($totalPendapatan,0,',','.') }}
    </p>

    <p><strong>Total Pengeluaran:</strong>
        Rp {{ number_format($totalPengeluaran,0,',','.') }}
    </p>

    <p><strong>Selisih:</strong>
        Rp {{ number_format($selisih,0,',','.') }}
    </p>
</div>

<h3>Rincian Pendapatan</h3>

<table>
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendapatan as $item)
        <tr>
            <td>{{ $item->kategori }}</td>
            <td class="text-right">
                Rp {{ number_format($item->total,0,',','.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Rincian Pengeluaran</h3>

<table>
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengeluaran as $item)
        <tr>
            <td>{{ $item->kategori }}</td>
            <td class="text-right">
                Rp {{ number_format($item->total,0,',','.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>