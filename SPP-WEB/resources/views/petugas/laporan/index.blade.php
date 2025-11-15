@extends('layouts.app')
@section('title', 'Laporan | SPP')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Laporan Pembayaran SPP</h2>
    <div class="d-flex">
        <a href="/pembayaran" class="btn btn-dark mr-2 my-2">Cetak PDF</a>
        <a href="/pembayaran" class="btn btn-dark m-2">Cetak Excel</a>
        <form action="/laporan/filter" method="get" class="d-flex align-items-center">

            {{-- Dropdown Filter --}}
            <div class="mx-2">
                <select name="filter" class="form-select my-2" style="width: 250px;" id="filter">
                    <option value="all" {{ ($filter ?? '') == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="today" {{ ($filter ?? '') == 'today' ? 'selected' : '' }}>Hari ini</option>
                    <option value="month" {{ ($filter ?? '') == 'month' ? 'selected' : '' }}>Bulan ini</option>
                    <option value="year" {{ ($filter ?? '') == 'year' ? 'selected' : '' }}>Tahun ini</option>
                    <option value="manual"{{ ($filter ?? '') == 'manual' ? 'selected' : '' }}>Tanggal</option>
                </select>
            </div>

            {{-- Form Tanggal Manual --}}
            <div id="tanggal" class="ms-auto {{ ($filter ?? '') == 'manual' ? '' : 'd-none' }}">
                <div class="d-flex m-2 align-items-center">
                    <input class="form-control" type="date" name="first" value="{{ $first ?? '' }}" />

                    <label class="mx-2">>></label>

                    <input class="form-control" type="date" name="second" value="{{ $second ?? '' }}" />

                    <button class="btn btn-outline-primary mx-2" type="submit">Filter</button>
                </div>
            </div>
        </form>

    </div>

    <table class="table border-top mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">NIS</th>
                <th scope="col">Kelas</th>
                <th scope="col">Tanggal Bayar</th>
                <th scope="col">Tahun Bayar</th>
                <th scope="col">Jumlah Bayar</th>
                <th scope="col">Nama Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $i => $p)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->nis }}</td>
                    <td>{{ $p->nama_kelas }} {{ $p->kompetensi_keahlian }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->isoFormat('dddd, D MMMM Y') }}</td>
                    <td>{{ $p->tahun_dibayar }}</td>
                    <td>{{ 'Rp ' . number_format($p->total_bayar, '0', ',', '.') }}</td>
                    <td>{{ $p->nama_petugas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($pesan = Session::get('success'))
        <script>
            alert('{{ $pesan }}');
        </script>
    @endif

    <script>
        const filter = document.getElementById('filter');
        const tanggalBox = document.getElementById('tanggal');

        filter.addEventListener('change', function() {
            if (this.value === 'manual') {
                tanggalBox.classList.remove('d-none');
            } else {
                tanggalBox.classList.add('d-none');
                this.form.submit(); // auto submit kecuali manual
            }
        });
    </script>

@endsection
