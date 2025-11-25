@extends('layouts.app')
@section('title', 'Beranda | Petugas')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection

@section('content')
    <div class="container mt-4">

        <h3 class="mb-4">Dashboard Petugas</h3>

        <div class="row">

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                    <h6>Total Siswa</h6>
                    <h3>{{ $totalSiswa }}</h3>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                    <h6>Total Transaksi</h6>
                    <h3>{{ $totalTransaksi }}</h3>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                    <h6>Pembayaran Hari Ini</h6>
                    <h3>Rp {{ number_format($totalPembayaranHariIni) }}</h3>
                </div>
            </div>

        </div>

        <div class="row mt-4">

            <div class="col-md-8">

                <div class="card shadow-sm" style="height: 280;">
                    <div class="card-header bg-primary text-white">
                        Riwayat Pembayaran Terbaru
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Tanggal</th>
                                    <th>Bulan Dibayar</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat as $row)
                                    <tr>
                                        <td>{{ $row->nisn }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->tgl_bayar)->isoFormat('dddd, D MMMM Y') }}</td>
                                        <td>{{ $row->bulan_dibayar }} {{ $row->tahun_dibayar }}</td>
                                        <td>Rp {{ number_format($row->jumlah_bayar) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        Status Pembayaran SPP Bulan Ini
                    </div>
                    <div class="card-body">
                        <div id="chartPie"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                Data Pembayaran Perbulan
            </div>
            <div class="card-body">
                <div id="chart"></div>
            </div>
        </div>

        @if (Auth::guard('petugas')->user()->level == 'admin')
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    Log Aktifitas
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Nama</th>
                                <th>Hak Akses</th>
                                <th>Aktifitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $row)
                                <tr>
                                    <td>{{ $row->created_at->diffForHumans() }}</td>
                                    @if ($row->nisn && !$row->id_petugas)
                                        <td>{{ $row->siswa->nama }}</td>
                                        <td>Siswa</td>
                                        <td>{{ $row->aktifitas }}</td>
                                    @elseif (!$row->nisn && $row->id_petugas)
                                        <td>{{ $row->petugas->nama_petugas }}</td>
                                        <td style="text-transform: capitalize;">{{ $row->petugas->level }}</td>
                                        <td>{{ $row->aktifitas }}</td>
                                    @elseif ($row->nisn && $row->id_petugas)
                                        <td>{{ $row->petugas->nama_petugas }}</td>
                                        <td style="text-transform: capitalize;">{{ $row->petugas->level }}</td>
                                        <td>{{ $row->aktifitas }} {{ $row->siswa->nama }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <script>
        var options = {
            series: [{
                name: 'Total Pembayaran',
                data: @json($totalBayarPerBulan)
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            colors: ['#2244FF', '#FF4422', '#22FF44', '#22BBAA', '#8AC23B', '#CBDFAB', 'FFFF00', '00FFFF', 'FF00FF'],
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: @json($bulan),
                labels: {
                    style: {
                        colors: ['#000000'],
                        fontSize: '12px',
                        fontFamily: 'Elms Sans'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return value.toLocaleString('id-ID', {
                            style: "currency",
                            currency: "IDR"
                        });
                    },
                    style: {
                        fontFamily: 'Elms Sans'
                    }
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

    <script>
        var pieOptions = {
            series: [{{ $sudahBayar }}, {{ $belumBayar }}],
            chart: {
                width: 380,
                height: 263,
                type: 'pie',
                fontFamily: 'Elms Sans', // 👈 font utama chart
            },
            labels: ['Sudah Bayar', 'Belum Bayar'], // 👈 perbaikan struktur
            colors: ['#4CAF50', '#F44336'],
            legend: {
                position: 'bottom',
                labels: {
                    colors: '#000', // warna text legend
                    useSeriesColors: false,
                    fontFamily: 'Elms Sans' // font legend
                }
            },
            dataLabels: {
                style: {
                    fontFamily: 'Elms Sans', // font angka di chart
                    fontSize: '14px'
                },
                formatter: function(val, opts) {
                    return opts.w.globals.series[opts.seriesIndex] + " siswa";
                }
            }
        };

        var pieChart = new ApexCharts(document.querySelector("#chartPie"), pieOptions);
        pieChart.render();
    </script>

@endsection
