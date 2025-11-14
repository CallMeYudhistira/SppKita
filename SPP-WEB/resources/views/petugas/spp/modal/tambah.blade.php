<div class="modal fade" id="tambahData" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/spp/tambah" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah SPP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="col-form-label">Tahun</label>
                        <input type="text" class="form-control" name="tahun" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Nominal (Rp.)</label>
                        <input type="text" class="form-control" name="nominal" autocomplete="off">
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
