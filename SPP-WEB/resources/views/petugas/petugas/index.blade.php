@extends('layouts.app')
@section('title', 'List | Petugas')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Kelola Data Petugas</h2>
    <div class="d-flex">
        <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#tambahData">Tambah</button>
        @include('petugas.petugas.modal.tambah')
        <form class="d-flex ms-auto my-2" action="/petugas/cari" method="get">
            <input class="form-control me-2" type="search" name="keyword" placeholder="Cari nama 🔍" autocomplete="off"
                @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </form>
    </div>

    <table class="table border-top mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Petugas</th>
                <th scope="col">Username</th>
                <th scope="col">Level</th>
                <th scope="col" colspan="2" class="text-center" style="width: 10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $i => $p)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $p->nama_petugas }}</td>
                    <td>{{ $p->username }}</td>
                    <td>{{ $p->level }}</td>
                    @if (Auth::guard('petugas')->user()->id_petugas == $p->id_petugas)
                        <td colspan="2">
                            <button class="btn w-100">-</button>
                        </td>
                    @else
                        <td>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#ubahData{{ $p->id_petugas }}">Ubah</button>
                        </td>
                        <td>
                            <form action="/petugas/hapus/{{ $p->id_petugas }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah anda ingin menghapus petugas?')">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>

                @include('petugas.petugas.modal.ubah')
            @endforeach
        </tbody>
    </table>


    @if ($pesan = Session::get('success'))
        <script>
            alert('{{ $pesan }}');
        </script>
    @endif
@endsection
