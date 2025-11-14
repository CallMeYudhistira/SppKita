<div class="modal fade" id="ubahData{{ $s->id_spp }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/spp/ubah/{{ $s->id_spp }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Ubah SPP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label class="col-form-label">Tahun</label>
                        <input type="text" class="form-control" name="tahun" value="{{ $s->tahun }}" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Nominal (Rp.)</label>
                        <input type="text" class="form-control" name="nominal" value="{{ $s->nominal }}" autocomplete="off">
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
