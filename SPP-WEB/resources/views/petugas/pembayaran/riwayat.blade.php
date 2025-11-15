@extends('layouts.app')
@section('title', 'Riwayat Bayar | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Riwayat Pembayaran SPP</h2>
    <a href="/pembayaran" class="btn btn-dark my-2">Kembali</a>

    <table class="table border-top mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">NIS</th>
                <th scope="col">Kelas</th>
                <th scope="col">Tanggal Bayar</th>
                <th scope="col">Bulan Dibayar</th>
                <th scope="col">Tahun Ajaran</th>
                <th scope="col">Jumlah Bayar</th>
                <th scope="col" class="text-center" style="width: 10%;">Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $i => $p)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $p->siswa->nama }}</td>
                    <td>{{ $p->siswa->nis }}</td>
                    <td>{{ $p->siswa->kelas->nama_kelas }} {{ $p->siswa->kelas->kompetensi_keahlian }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->isoFormat('dddd, D MMMM Y') }}</td>
                    <td>{{ $p->bulan_dibayar }}</td>
                    <td>{{ $p->tahun_dibayar }}/{{ $p->tahun_dibayar + 1 }}</td>
                    <td>{{ 'Rp ' . number_format($p->jumlah_bayar, '0', ',', '.') }}</td>
                    <td class="text-center">
                        <a href="/pembayaran/detail/{{ $p->id_pembayaran }}" class="btn btn-success">Detail</a>
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
