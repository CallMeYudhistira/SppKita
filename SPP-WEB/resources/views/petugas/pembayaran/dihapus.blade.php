@extends('layouts.app')
@section('title', 'Transaksi Gagal | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Transaksi Yang Dihapus</h2>
    <div class="d-flex">
        <a href="/pembayaran/riwayat" class="btn btn-dark my-2">Kembali</a>
        <form class="d-flex ms-auto my-2" action="/pembayaran/riwayat/gagal/filter" method="get">
            <input type="date" name="dari" class="form-control me-2" @isset($dari) value="{{ $dari }}" @endisset>
            <label class="form-label m-2">>></label>
            <input type="date" name="sampai" class="form-control mx-2" @isset($sampai) value="{{ $sampai }}" @endisset>
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </form>
    </div>
    <div class="my-3">
        <table class="table border-top mt-4">
            <thead>
                <tr>
                    <th scope="col">NIS</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Nominal</th>
                    <th scope="col">Bulan Yang Dibayar</th>
                    <th scope="col">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran as $i => $p)
                    <tr>
                        <td>{{ $p->siswa->nis }}</td>
                        <td>{{ $p->siswa->nama }}</td>
                        <td>{{ $p->siswa->kelas->nama_kelas }} {{ $p->siswa->kelas->kompetensi_keahlian }}</td>
                        <td>{{ 'Rp ' . number_format($p->jumlah_bayar, '0', ',', '.') }}</td>
                        <td>{{ $p->bulan_dibayar }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->isoFormat("dddd, DD MMMM Y") }}</td>
                    </tr>
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
