@extends('layouts.app')
@section('title', 'Detail Bayar | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Detail Pembayaran</h2>
    <div class="d-flex">
        <a href="/pembayaran/riwayat" class="btn btn-dark my-2">Kembali</a>
        <a href="/pembayaran/cetak/kartu/{{ $siswa->nisn }}" class="btn btn-secondary m-2" target="_blank">Cetak Kartu</a>
        <button type="button" class="btn btn-success my-2" data-bs-toggle="modal" data-bs-target="#bayarSPP{{ $s->nisn }}">
            Bayar
        </button>
        @include('petugas.pembayaran.modal.bayar')
    </div>
    <div style="margin: auto; margin-top: 3vh; margin-bottom: 6vh;">
        <div class="row align-items-center m-2">
            <div class="col-2">
                NISN
            </div>
            <div class="col-4">
                : {{ $siswa->nisn }}
            </div>
            <div class="col-2">
                NIS
            </div>
            <div class="col-4">
                : {{ $siswa->nis }}
            </div>
        </div>
        <div class="row align-items-center m-2">
            <div class="col-2">
                Nama
            </div>
            <div class="col-4">
                : {{ $siswa->nama }}
            </div>
            <div class="col-2">
                Kelas
            </div>
            <div class="col-4">
                : {{ $siswa->nama_kelas }} {{ $siswa->kompetensi_keahlian }}
            </div>
        </div>
        <div class="row align-items-center m-2">
            <div class="col-2">
                Nominal SPP
            </div>
            <div class="col-4">
                : {{ 'Rp ' . number_format($siswa->nominal, '0', ',', '.') }} - {{ $siswa->tahun }}
            </div>
            <div class="col-2">
                Bulan Sudah Dibayar
            </div>
            <div class="col-4">
                : {{ $siswa->bulan_dibayar }} dari 12 Bulan
            </div>
        </div>
        <div class="row align-items-center m-2">
            <div class="col-2">
                Total Dibayar
            </div>
            <div class="col-4">
                : {{ 'Rp ' . number_format($siswa->total_bayar, '0', ',', '.') }}
            </div>
            <div class="col-2">
                Tunggakan
            </div>
            <div class="col-4">
                : {{ 'Rp ' . number_format(($siswa->total_bayar - $siswa->nominal * 12) * -1, '0', ',', '.') }}
            </div>
        </div>
    </div>
    <div class="my-3">
        <table class="table border-top mt-4">
            <thead>
                <tr>
                    <th scope="col">Bulan Dibayar</th>
                    <th scope="col">Tanggal Bayar</th>
                    <th scope="col">Nama Petugas</th>
                    <th scope="col" class="text-center" style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bulan as $b)
                    @php
                        $p = $pembayaran->firstWhere('bulan_dibayar', $b);
                    @endphp

                    @if ($p)
                        <tr>
                            <td>{{ $b }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->isoFormat('dddd, DD MMMM Y') }}</td>
                            <td>{{ $p->petugas->nama_petugas }}</td>
                            <td class="text-center">
                                <a href="/pembayaran/cetak/{{ $p->id_pembayaran }}" class="btn btn-success" target="_blank">Cetak</a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $b }}</td>
                            <td colspan="3">
                                <span class="btn">Belum Bayar ❌</span>
                            </td>
                        </tr>
                    @endif
                @endforeach

            </tbody>
        </table>
    </div>

    @if ($pesan = Session::get('success'))
        <script>
            alert('{{ $pesan }}');
        </script>
    @endif
@endsection
