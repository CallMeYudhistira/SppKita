<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan | PDF</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        table.header tr td {
            border: none;
        }

        .header td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .header-title {
            font-weight: bold;
            text-transform: uppercase;
        }

        .center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #000;
            padding: 5px 4px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .no-border td {
            border: none;
            padding: 3px 4px;
        }

        .bold {
            font-weight: bold;
        }

        .totals td {
            border-top: 1px solid #000;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
        }

        @page {
            margin: 20px;
        }
    </style>
</head>

<body>
    <h2 class="center">LAPORAN PEMBAYARAN</h2>
    <br>
    <br>
    <table class="header">
        <tr>
            <td class="header-title">Periode :
            @if ($filter)
            {{ $filter }}
            @else
            {{ $first }} - {{ $second }}
            @endif
            </td>
        </tr>
    </table>
    <br>
    <!-- Tabel Data Penjualan -->
    <table>
        <thead>
            <tr>
                <th style="width: 18%;">Tanggal Bayar</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Tahun Dibayar</th>
                <th>Bulan Dibayar</th>
                <th>Total Bayar</th>
                <th>Nama Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->translatedFormat('d-M-Y') }}</td>
                <td>{{ $p->nis }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->nama_kelas }} {{ $p->kompetensi_keahlian }}</td>
                <td>{{ $p->tahun_dibayar }}</td>
                <td>{{ $p->bulan_dibayar }}</td>
                <td>{{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                <td>{{ $p->nama_petugas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <div class="footer center">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y - H:i') }}</p>
    </div>
</body>

</html>
