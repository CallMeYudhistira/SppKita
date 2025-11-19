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

    <table class="table border-top mt-4">
        <thead>
            <tr>
                <th scope="col">NIS</th>
                <th scope="col">Nama</th>
                @foreach ($bulan as $b)
                    <th scope="col">{{ $b }}</th>
                @endforeach
                <th scope="col" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $s)
                <tr>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama }}</td>
                    @foreach ($bulan as $i => $b)
                        @if (!$pembayaran->where('nisn', $s->nisn)->where('bulan_dibayar', $b)->isEmpty())
                            <td class="text-center">✅</td>
                        @else
                            <td class="text-center">❌</td>
                        @endif
                    @endforeach
                    @php
                        $paidMonths = $pembayaran->where('nisn', $s->nisn)->pluck('bulan_dibayar')->toArray();
                        $sudahLunas = count($paidMonths) >= 12;
                    @endphp
                    <td class="text-center">
                        @if ($sudahLunas)
                            <span class="btn">Lunas ✔</span>
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

    <script>
        const filter = document.getElementById('id_kelas');

        filter.addEventListener('change', function() {
            this.form.submit();
        });
    </script>
@endsection
