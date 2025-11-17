<div class="modal fade" id="tambahData" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/kelas/tambah" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Kelas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="col-form-label">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Kompetensi Keahlian</label>
                        <select name="kompetensi_keahlian" class="form-select">
                            <option selected disabled>-- Pilih Kompetensi Keahlian --</option>
                            <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Elektronika Industri">Teknik Elektronika Industri</option>
                            <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan</option>
                            <option value="Teknik Pendingin dan Tata Udara">Teknik Pendingin dan Tata Udara</option>
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
