@extends('layouts.app')
@section('title', 'Beranda | Siswa')
@section('navbar')
    @include('layouts.navbar-siswa')
@endsection

@section('content')
    <div class="container mt-4">

        <h3 class="mb-3">Halo, {{ $siswa->nama }}</h3>

        <div class="row">

            <div class="col-md-3 mb-3">
                <div class="card p-3 shadow-sm">
                    <h6>SPP per Bulan</h6>
                    <h3>Rp {{ number_format($tagihan) }}</h3>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card p-3 shadow-sm">
                    <h6>Tahun Angkatan</h6>
                    <h3>{{ $tahun }}</h3>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card p-3 shadow-sm">
                    <h6>Total Sudah Bayar</h6>
                    <h3>Rp {{ number_format($totalSudahBayar) }}</h3>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card p-3 shadow-sm">
                    <h6>Total Tunggakan</h6>
                    <h3 class="text-danger">Rp {{ number_format($tunggakan) }}</h3>
                </div>
            </div>
        </div>

        <h3 class="my-3">Data Pembayaran SPP</h3>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        Semester Satu
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semesterSatu as $row)
                                    <tr>
                                        <td>{{ $row['bulan_dibayar'] }}</td>
                                        @if ($row['jumlah_bayar'])
                                        <td>Lunas ✅</td>
                                        @else
                                        <td>Belum Bayar ❌</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Semester Dua
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semesterDua as $row)
                                    <tr>
                                        <td>{{ $row['bulan_dibayar'] }}</td>
                                        @if ($row['jumlah_bayar'])
                                        <td>Lunas ✅</td>
                                        @else
                                        <td>Belum Bayar ❌</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
