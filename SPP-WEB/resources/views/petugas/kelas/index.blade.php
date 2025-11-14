@extends('layouts.app')
@section('title', 'List | Kelas')
@section('content')
    <h2>Kelola Data Kelas</h2>
    <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#tambahData">Tambah</button>
    @include('petugas.kelas.modal.tambah')

    <table class="table border-top mt-5">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Kelas</th>
                <th scope="col">Kompetensi Keahlian</th>
                <th scope="col" colspan="2" class="text-center" style="width: 10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelas as $i => $k)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $k->nama_kelas }}</td>
                    <td>{{ $k->kompetensi_keahlian }}</td>
                    <td><button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#ubahData{{ $k->id_kelas }}">Ubah</button></td>
                    <td>
                        <form action="/kelas/hapus/{{ $k->id_kelas }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah anda ingin menghapus kelas?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                @include('petugas.kelas.modal.ubah')
            @endforeach
        </tbody>
    </table>

    @if ($pesan = Session::get('success'))
        <script>
            alert('{{ $pesan }}');
        </script>
    @endif
@endsection
