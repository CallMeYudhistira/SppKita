@extends('layouts.app')
@section('title', 'List | Siswa')
@section('navbar')
    @include('layouts.navbar-petugas')
@endsection
@section('content')
    <h2>Kelola Data Siswa</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahData">Tambah</button>

    <div class="modal fade" id="tambahData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Tambah Siswa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label class="col-form-label">NISN</label>
                            <input type="text" class="form-control" name="nisn" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">NIS</label>
                            <input type="text" class="form-control" name="nis" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2" autocomplete="off"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nomor Telepon</label>
                            <input type="number" class="form-control" name="no_telp" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Username</label>
                            <input type="text" class="form-control" name="username" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Password</label>
                            <input type="text" class="form-control" name="password" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
