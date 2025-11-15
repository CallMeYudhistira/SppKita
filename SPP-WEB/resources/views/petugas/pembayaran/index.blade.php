@extends('layouts.app')
@section('title', 'Pembayaran | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Pembayaran SPP</h2>
    <a href="/pembayaran/riwayat" class="btn btn-secondary my-2">Riwayat Pembayaran</a>

    <table class="table border-top mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">NIS</th>
                <th scope="col">Kelas</th>
                <th scope="col">Tahun Ajaran</th>
                <th scope="col">SPP</th>
                <th scope="col" class="text-center" style="width: 10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $i => $s)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->kelas->nama_kelas }} {{ $s->kelas->kompetensi_keahlian }}</td>
                    <td>{{ now()->format('Y') }}/{{ now()->format('Y') + 1 }}</td>
                    <td>{{ 'Rp ' . number_format($s->spp->nominal, '0', ',', '.') }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bayarSPP{{ $s->nisn }}">Bayar</button>
                    </td>
                </tr>

                @include('petugas.pembayaran.modal.bayar')
            @endforeach
        </tbody>
    </table>

    @if ($pesan = Session::get('success'))
        <script>
            alert('{{ $pesan }}');
        </script>
    @endif
@endsection
