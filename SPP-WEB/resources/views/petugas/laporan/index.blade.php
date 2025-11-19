@extends('layouts.app')
@section('title', 'Laporan | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Laporan Pembayaran SPP</h2>
    <div class="d-flex">
        <form action="/laporan/cetak" method="get" class="my-2" target="_blank">
            <input type="hidden" name="id_kelas" @isset($id_kelas) value="{{ $id_kelas }}" @endisset>
            <button type="submit" class="btn btn-success">Cetak</button>
        </form>
        <form class="d-flex ms-auto my-2" action="/laporan/cari" method="get">
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
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($pesan = Session::get('error'))
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
