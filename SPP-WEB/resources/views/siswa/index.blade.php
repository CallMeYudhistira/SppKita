@extends('layouts.app')
@section('title', 'Beranda | Siswa')
@section('navbar')
    @include('layouts.navbar-siswa')
@endsection

@section('content')
    <div class="container mt-4">

        <h3 class="mb-3">Halo, {{ $siswa->nama }}</h3>

        <div class="row">

            <div class="col-md-4 mb-3">
                <div class="card p-3 shadow-sm">
                    <h6>SPP per Bulan</h6>
                    <h3>Rp {{ number_format($tagihan) }}</h3>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 shadow-sm">
                    <h6>Total Sudah Bayar</h6>
                    <h3>Rp {{ number_format($totalSudahBayar) }}</h3>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 shadow-sm">
                    <h6>Total Tunggakan</h6>
                    <h3 class="text-danger">Rp {{ number_format($tunggakan) }}</h3>
                </div>
            </div>

        </div>

        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                Pembayaran Terakhir
            </div>
            <div class="card-body">
                @if ($riwayatTerakhir)
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($riwayatTerakhir->tgl_bayar)->isoFormat("dddd, D MMMM Y") }}</p>
                    <p><strong>Bulan Dibayar:</strong>
                        {{ $riwayatTerakhir->bulan_dibayar }} {{ $riwayatTerakhir->tahun_dibayar }}
                    </p>
                    <p><strong>Jumlah:</strong>
                        Rp {{ number_format($riwayatTerakhir->jumlah_bayar) }}
                    </p>
                @else
                    <p class="text-danger">Belum ada riwayat pembayaran.</p>
                @endif
            </div>
        </div>

    </div>
@endsection
