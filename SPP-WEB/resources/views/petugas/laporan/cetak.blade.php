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
            text-align: left;
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
    <h2 class="center">LAPORAN PEMBAYARAN SPP</h2>
    <h3 class="center">Kelas {{ $kelas->nama_kelas }} {{ $kelas->kompetensi_keahlian }}</h3>
    <br>
    <!-- Tabel Data Penjualan -->
    <table>
        <thead>
            <tr>
                <th scope="col">NIS</th>
                <th scope="col">Nama</th>
                @foreach ($bulan as $b)
                    <th scope="col">{{ $b }}</th>
                @endforeach
                <th scope="col">Total</th>
                <th scope="col">Tunggakan</th>
            </tr>
        </thead>
        <tbody>
    @php
        $totalKelas = 0;
        $tunggakanKelas = 0;
    @endphp

    @foreach ($siswa as $s)
        @php
            $totalSiswa = 0;
            $wajibBayar = $s->spp->nominal * 12;
        @endphp

        <tr>
            <td>{{ $s->nis }}</td>
            <td>{{ $s->nama }}</td>

            @foreach ($bulan as $b)
                @php
                    $bayar = $pembayaran
                        ->where('nisn', $s->nisn)
                        ->where('bulan_dibayar', $b)
                        ->first();
                @endphp

                @if ($bayar)
                    <td>Rp {{ number_format($s->spp->nominal, 0, ',', '.') }}</td>
                    @php
                        $totalSiswa += $s->spp->nominal;
                    @endphp
                @else
                    <td>Rp 0</td>
                @endif
            @endforeach

            @php
                $tunggakanSiswa = $wajibBayar - $totalSiswa;
            @endphp

            <td class="bold">Rp {{ number_format($totalSiswa, 0, ',', '.') }}</td>
            <td class="bold">Rp {{ number_format($tunggakanSiswa, 0, ',', '.') }}</td>
        </tr>

        @php
            $totalKelas += $totalSiswa;
            $tunggakanKelas += $tunggakanSiswa;
        @endphp

    @endforeach

    <tr class="bold">
        <td colspan="{{ 2 + count($bulan) }}" style="text-align:right;">
            TOTAL KELAS :
        </td>
        <td>
            Rp {{ number_format($totalKelas, 0, ',', '.') }}
        </td>
        <td>
            Rp {{ number_format($tunggakanKelas, 0, ',', '.') }}
        </td>
    </tr>
</tbody>


    </table>
    <br>
    <div class="footer center">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y - H:i') }}</p>
    </div>
</body>

</html>
