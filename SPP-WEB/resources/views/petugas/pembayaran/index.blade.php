@extends('layouts.app')
@section('title', 'Pembayaran | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Pembayaran SPP</h2>
    <div class="d-flex">
        <a href="/pembayaran/riwayat" class="btn btn-secondary my-2">Riwayat Pembayaran</a>
        <form class="d-flex ms-auto my-2" action="/pembayaran/cari" method="get">
            <input class="form-control me-2" type="search" name="keyword" placeholder="Cari nama siswa 🔍" autocomplete="off"
                @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </form>
    </div>

    <table class="table border-top mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">NIS</th>
                <th scope="col">Kelas</th>
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
                    <td>{{ 'Rp ' . number_format($s->spp->nominal, '0', ',', '.') }}</td>
                    @php
                        $paidMonths = $pembayaran->where('nisn', $s->nisn)->pluck('bulan_dibayar')->toArray();
                        $sudahLunas = count($paidMonths) >= 12;
                    @endphp
                    <td class="text-center">

                        @if ($sudahLunas)
                            <span class="badge bg-success">Lunas ✔</span>
                        @else
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#bayarSPP{{ $s->nisn }}">
                                Bayar
                            </button>
                        @endif

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
