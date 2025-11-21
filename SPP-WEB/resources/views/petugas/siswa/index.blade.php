@extends('layouts.app')
@section('title', 'List | Siswa')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Kelola Data Siswa</h2>
    <div class="d-flex">
        <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#tambahData">Tambah</button>
        @include('petugas.siswa.modal.tambah')
        <form class="d-flex ms-auto my-2" action="/siswa/cari" method="get">
            <div class="mx-2">
                <select name="id_kelas" class="form-select" style="width: 350px;" id="id_kelas">
                    <option selected value="semua">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        @php
                            $nama_kelas = $k->nama_kelas . ' ' . $k->kompetensi_keahlian;
                        @endphp
                        <option value="{{ $k->id_kelas }}"
                            @isset($id_kelas) {{ $id_kelas == $k->id_kelas ? 'selected' : '' }} @endisset>
                            {{ $nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <input class="form-control me-2" type="search" name="keyword" placeholder="Cari nama siswa 🔍"
                autocomplete="off" @isset($keyword) value="{{ $keyword }}" @endisset />
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </form>
    </div>

    <div class="table-responsive" style="max-width:100%; overflow-x: scroll; white-space: nowrap;">
        <table class="table border-top mt-4">
            <thead>
                <tr>
                    <th scope="col" style="width: 20%;">NISN</th>
                    <th scope="col" style="width: 20%;">NIS</th>
                    <th scope="col" style="width: 20%;">Nama</th>
                    <th scope="col" style="width: 20%;">Kelas</th>
                    <th scope="col" style="width: 20%;">Alamat</th>
                    <th scope="col" style="width: 20%;">Nomor Telepon</th>
                    <th scope="col" style="width: 20%;">SPP</th>
                    <th scope="col" style="width: 20%;">Username</th>
                    <th scope="col" colspan="2" class="text-center" style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $i => $s)
                    <tr>
                        <td>{{ $s->nisn }}</td>
                        <td>{{ $s->nis }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->kelas->nama_kelas }} {{ $s->kelas->kompetensi_keahlian }}</td>
                        <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                            title="{{ $s->alamat }}">{{ $s->alamat }}</td>
                        <td>{{ $s->no_telp }}</td>
                        <td>{{ 'Rp ' . number_format($s->spp->nominal, '0', ',', '.') }}</td>
                        <td>{{ $s->username }}</td>
                        <td>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#ubahData{{ $s->nisn }}">Ubah</button>
                        </td>
                        <td>
                            <form action="/siswa/hapus/{{ $s->nisn }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah anda ingin menghapus siswa?')">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    @include('petugas.siswa.modal.ubah')
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const filter = document.getElementById('id_kelas');

        filter.addEventListener('change', function() {
            this.form.submit();
        });
    </script>
@endsection
