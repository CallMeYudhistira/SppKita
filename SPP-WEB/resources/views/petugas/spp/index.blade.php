@extends('layouts.app')
@section('title', 'List | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Kelola Data SPP</h2>
    <div class="d-flex">
        <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#tambahData">Tambah</button>
        @include('petugas.spp.modal.tambah')
        <form class="d-flex ms-auto my-2" action="/spp/cari" method="get">
            <input class="form-control me-2" type="search" name="keyword" placeholder="Cari tahun 🔍" autocomplete="off"
                @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </form>
    </div>

    <table class="table border-top mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tahun</th>
                <th scope="col">Nominal</th>
                <th scope="col" colspan="2" class="text-center" style="width: 10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($spp as $i => $s)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $s->tahun }}</td>
                    <td>{{ 'Rp ' . number_format($s->nominal, '0', ',', '.') }}</td>
                    <td><button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#ubahData{{ $s->id_spp }}">Ubah</button></td>
                    <td>
                        <form action="/spp/hapus/{{ $s->id_spp }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah anda ingin menghapus SPP?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                @include('petugas.spp.modal.ubah')
            @endforeach
        </tbody>
    </table>
@endsection
