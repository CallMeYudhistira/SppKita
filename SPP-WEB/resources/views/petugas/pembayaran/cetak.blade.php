<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran | Invoice</title>
    <style>
        /* ====== STYLE DASAR (PENGGANTI BOOTSTRAP) ====== */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #212529;
            background-color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .card {
            margin: auto;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h4 {
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-muted {
            color: #6c757d;
        }

        .small {
            font-size: 13px;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-1 {
            margin-bottom: 4px !important;
        }

        .mb-3 {
            margin-bottom: 16px !important;
        }

        .mt-3 {
            margin-top: 16px !important;
        }

        .shadow {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .p-4 {
            padding: 24px !important;
        }

        hr {
            border: 0;
            border-top: 1px solid #dee2e6;
            margin: 12px 0;
        }

        /* ====== TABEL ====== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            padding: 4px 0;
            vertical-align: top;
        }

        tr td {
            font-size: 0.7rem;
        }

        /* ====== WARNA DAN FONT TAMBAHAN ====== */
        .text-muted {
            color: #6c757d !important;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container py-5 d-flex justify-content-center" style="display: flex; justify-content: center;">
        <div class="card shadow p-4"
            style="margin-top: 5vh; width: 22rem; font-family: 'Courier New', monospace; background-color: #fff; border: 1px solid #dee2e6; border-radius: 8px; padding: 24px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <div class="text-center mb-1" style="text-align: center; margin-bottom: 4px;">
                <h4 class="fw-bold text-uppercase" style="font-weight: bold; text-transform: uppercase; margin: 0;">
                    Pembayaran SPP</h4>
            </div>
            <div class="text-center" style="text-align: center; margin: 0.5rem;">
                <p style="text-transform: uppercase; margin: 0; font-size: 0.7rem;">
                    {{ \Carbon\Carbon::parse($pembayaran->tgl_bayar)->isoFormat('dddd, D MMMM Y') }}</p>
            </div>

            <hr style="border: 0; border-top: 1px solid #dee2e6; margin: 12px 0;">

            <div class="small" style="font-size: 13px;">
                <p><strong>Nis:</strong> {{ $pembayaran->tgl_bayar }}</p>
                <p><strong>Nama:</strong> {{ $pembayaran->nama }}</p>
                <p><strong>Kelas:</strong> {{ $pembayaran->nama_kelas }} {{ $pembayaran->kompetensi_keahlian }}</p>
            </div>

            <hr style="border: 0; border-top: 1px solid #dee2e6; margin: 12px 0;">

            <table class="table table-borderless small mb-0"
                style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 0;">
                <thead>
                    <tr>
                        <td>Tahun</td>
                        <td>Bulan</td>
                        <td>Nominal</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail_pembayaran as $p)
                        <tr>
                            <td>{{ $p->tahun_dibayar }}</td>
                            <td>{{ $p->bulan_dibayar }}</td>
                            <td>{{ 'Rp ' . number_format($p->jumlah_bayar, '0', ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr style="border: 0; border-top: 1px solid #dee2e6; margin: 12px 0;">

            <table class="table table-borderless small mb-0"
                style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 0;">
                <tr>
                    <td>Total</td>
                    <td class="text-end" style="text-align: right;">{{ 'Rp ' . number_format($pembayaran->total_bayar, '0', ',', '.') }}</td>
                </tr>
            </table>

            <hr style="border: 0; border-top: 1px solid #dee2e6; margin: 12px 0;">

            <p class="small text-end mb-1" style="font-size: 13px; text-align: right; margin-bottom: 4px;">Petugas: {{ $pembayaran->nama_petugas }}</p>
        </div>
    </div>
</body>

</html>
