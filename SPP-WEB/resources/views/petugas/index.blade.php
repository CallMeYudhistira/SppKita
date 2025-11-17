@extends('layouts.app')
@section('title', 'Beranda | Petugas')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection

@section('content')
    <div class="container mt-4">

        <h3 class="mb-4">Dashboard Petugas</h3>

        <div class="row">

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm p-3">
                    <h6>Total Siswa</h6>
                    <h3>{{ $totalSiswa }}</h3>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm p-3">
                    <h6>Total Transaksi</h6>
                    <h3>{{ $totalTransaksi }}</h3>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm p-3">
                    <h6>Pembayaran Hari Ini</h6>
                    <h3>Rp {{ number_format($totalPembayaranHariIni) }}</h3>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm p-3">
                    <h6>Total Tunggakan</h6>
                    <h3>Rp {{ number_format($totalTunggakan) }}</h3>
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
                                <td>{{ \Carbon\Carbon::parse($row->tgl_bayar)->isoFormat("dddd, D MMMM Y") }}</td>
                                <td>{{ $row->bulan_dibayar }} {{ $row->tahun_dibayar }}</td>
                                <td>Rp {{ number_format($row->jumlah_bayar) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
