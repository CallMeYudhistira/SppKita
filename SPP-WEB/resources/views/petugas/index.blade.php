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

        <div class="card mt-4 shadow-sm">
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

        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                Data Pembayaran Perbulan
            </div>
            <div class="card-body">
                {{-- <canvas id="chartSPP"></canvas> --}}
                <div class="d-flex" style="width: fit-content; margin: 6px auto;">
                    @foreach ($bulan as $b)
                        <div style="width: 90px; margin: 0 8px;" class="text-center">{{ $b }}</div>
                    @endforeach
                </div>
                <div class="d-flex" style="width: fit-content; margin: 0 auto;">
                    @foreach ($bulanNomer as $i => $b)
                    @php
                        $data = $pembayaran->whereBetween('tgl_bayar', [now()->format('Y') . '-' . $b . '-01', now()->format('Y') . '-' . $b . '-31'])->count();
                    @endphp
                        <div style="width: 90px; height: {{ $data > 0 ? $data . 'rem' : '0px' }}; background-color: {{ $color[$i] }}; margin: 0 8px; max-height: 400px;"></div>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </div>
                <div class="d-flex" style="width: fit-content; margin: 6px auto;">
                    @foreach ($bulanNomer as $b)
                    @php
                        $data = $pembayaran->whereBetween('tgl_bayar', [now()->format('Y') . '-' . $b . '-01', now()->format('Y') . '-' . $b . '-31'])->count();
                    @endphp
                        <div style="width: 90px; margin: 3px 8px;" class="text-center">{{ $data }}</div>
                    @endforeach
                </div>
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
@endsection
