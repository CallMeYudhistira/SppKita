<div class="modal fade" id="ubahData{{ $s->nisn }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/siswa/ubah/{{ $s->nisn }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Ubah Siswa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('put')
                        <div class="mb-3">
                            <label class="col-form-label">NISN</label>
                            <input type="text" class="form-control" name="nisn" autocomplete="off" value="{{ $s->nisn }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">NIS</label>
                            <input type="text" class="form-control" name="nis" autocomplete="off" value="{{ $s->nis }}">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" autocomplete="off" value="{{ $s->nama }}">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Kelas</label>
                            <select name="id_kelas" class="form-select">
                                <option disabled>-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ $k->id_kelas == $s->kelas->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }} {{ $k->kompetensi_keahlian }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2" autocomplete="off">{{ $s->alamat }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nomor Telepon</label>
                            <input type="number" class="form-control" name="no_telp" autocomplete="off" value="{{ $s->no_telp }}">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">SPP</label>
                            <select name="id_spp" class="form-select">
                                <option selected disabled>-- Pilih SPP --</option>
                                @foreach ($spp as $_spp)
                                <option value="{{ $_spp->id_spp }}" {{ $_spp->id_spp == $s->spp->id_spp ? 'selected' : '' }}>{{ 'Rp ' . number_format($_spp->nominal, '0', ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Username</label>
                            <input type="text" class="form-control" name="username" autocomplete="off" value="{{ $s->username }}">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Password</label>
                            <input type="text" class="form-control mb-1" name="password" autocomplete="off">
                            <small style="color: #999; font-size: 0.8rem;">Kosongkan jika tidak mengubah password</small>
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
