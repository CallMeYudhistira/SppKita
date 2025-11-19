@extends('layouts.app')
@section('title', 'Riwayat Bayar | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Riwayat Pembayaran SPP</h2>
    <div class="d-flex">
        <a href="/pembayaran" class="btn btn-dark my-2">Kembali</a>
        <form class="d-flex ms-auto my-2" action="/pembayaran/riwayat/cari" method="get">
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
    <div class="my-3">
        <table class="table border-top mt-4">
            <thead>
                <tr>
                    <th scope="col">NIS</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Nominal SPP</th>
                    <th scope="col">Bulan Sudah Dibayar</th>
                    <th scope="col">Total Dibayar</th>
                    <th scope="col" class="text-center" style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran as $i => $p)
                    <tr>
                        <td>{{ $p->nis }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->nama_kelas }} {{ $p->kompetensi_keahlian }}</td>
                        <td>{{ 'Rp ' . number_format($p->nominal, '0', ',', '.') }} - {{ $p->tahun }}</td>
                        <td>{{ $p->bulan_dibayar }} dari 12 Bulan</td>
                        <td>{{ 'Rp ' . number_format($p->total_bayar, '0', ',', '.') }}</td>
                        <td class="text-center">
                            <a href="/pembayaran/detail/{{ $p->nisn }}" class="btn btn-success">Detail</a>
                        </td>
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

    <script>
        const filter = document.getElementById('id_kelas');

        filter.addEventListener('change', function() {
            this.form.submit();
        });
    </script>
@endsection
