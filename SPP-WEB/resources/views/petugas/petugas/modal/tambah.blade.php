    <div class="modal fade" id="tambahData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="/petugas/tambah" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Tambah Petugas</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label class="col-form-label">Nama Petugas</label>
                            <input type="text" class="form-control" name="nama_petugas" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Username</label>
                            <input type="text" class="form-control" name="username" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Password</label>
                            <input type="text" class="form-control" name="password" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Level</label>
                            <select name="level" class="form-select">
                                <option selected disabled>-- Pilih Level --</option>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
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
