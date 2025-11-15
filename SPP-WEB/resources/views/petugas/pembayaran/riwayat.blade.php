@extends('layouts.app')
@section('title', 'Riwayat Bayar | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Riwayat Pembayaran SPP</h2>
    <a href="/pembayaran" class="btn btn-dark my-2">Kembali</a>
    <div class="mx-3">

        <table class="table border-top mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NIS</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Tanggal Bayar</th>
                    <th scope="col">Tahun Bayar</th>
                    <th scope="col">Jumlah Bayar</th>
                    <th scope="col">Nama Petugas</th>
                    <th scope="col" class="text-center" style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran as $i => $p)
                    <tr>
                        <th scope="row">{{ $i + 1 }}</th>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->nis }}</td>
                        <td>{{ $p->nama_kelas }} {{ $p->kompetensi_keahlian }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->isoFormat('dddd, D MMMM Y') }}</td>
                        <td>{{ $p->tahun_dibayar }}</td>
                        <td>{{ 'Rp ' . number_format($p->total_bayar, '0', ',', '.') }}</td>
                        <td>{{ $p->nama_petugas }}</td>
                        <td class="text-center">
                            <a href="/pembayaran/cetak/{{ $p->nisn }}/{{ $p->tgl_bayar }}"
                                class="btn btn-success">Cetak</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($pesan = Session::get('success'))
            <script>
                alert('{{ $pesan }}');
            </script>
        @endif
    @endsection
