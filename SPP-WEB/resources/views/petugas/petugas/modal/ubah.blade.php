<div class="modal fade" id="ubahData{{ $p->id_petugas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/petugas/ubah/{{ $p->id_petugas }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Petugas Siswa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label class="col-form-label">Nama Petugas</label>
                        <input type="text" class="form-control" name="nama_petugas" autocomplete="off"
                            value="{{ $p->nama_petugas }}">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Username</label>
                        <input type="text" class="form-control" name="username" autocomplete="off"
                            value="{{ $p->username }}">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Password</label>
                        <input type="text" class="form-control" name="password" autocomplete="off">
                            <small style="color: #999; font-size: 0.8rem;">Kosongkan jika tidak mengubah password</small>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Level</label>
                        <select name="level" class="form-select">
                            <option disabled>-- Pilih Level --</option>
                            <option value="admin" {{ $p->level == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ $p->level == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        </select>
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
