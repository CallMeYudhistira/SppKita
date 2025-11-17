    <div class="modal fade" id="tambahData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="/siswa/tambah" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Tambah Siswa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label class="col-form-label">NISN</label>
                            <input type="number" class="form-control" name="nisn" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">NIS</label>
                            <input type="number" class="form-control" name="nis" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Kelas</label>
                            <select name="id_kelas" class="form-select">
                                <option selected disabled>-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }} {{ $k->kompetensi_keahlian }}</option>
                                @endforeach
                            </select>
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
                            <label class="col-form-label">SPP</label>
                            <select name="id_spp" class="form-select">
                                <option selected disabled>-- Pilih SPP --</option>
                                @foreach ($spp as $_spp)
                                <option value="{{ $_spp->id_spp }}">{{ 'Rp ' . number_format($_spp->nominal, '0', ',', '.') }} - {{ $_spp->tahun }}</option>
                                @endforeach
                            </select>
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
