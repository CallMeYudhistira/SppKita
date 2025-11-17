<div class="modal fade" id="ubahData{{ $k->id_kelas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/kelas/ubah/{{ $k->id_kelas }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Ubah Kelas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label class="col-form-label">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" value="{{ $k->nama_kelas }}" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Kompetensi Keahlian</label>
                        <select name="kompetensi_keahlian" class="form-select">
                            <option disabled>-- Pilih Kompetensi Keahlian --</option>
                            <option value="Rekayasa Perangkat Lunak" {{ $k->kompetensi_keahlian == "Rekayasa Perangkat Lunak" ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Elektronika Industri" {{ $k->kompetensi_keahlian == "Teknik Elektronika Industri" ? 'selected' : '' }}>Teknik Elektronika Industri</option>
                            <option value="Teknik Komputer dan Jaringan" {{ $k->kompetensi_keahlian == "Teknik Komputer dan Jaringan" ? 'selected' : '' }}>Teknik Komputer dan Jaringan</option>
                            <option value="Teknik Pendingin dan Tata Udara" {{ $k->kompetensi_keahlian == "Teknik Pendingin dan Tata Udara" ? 'selected' : '' }}>Teknik Pendingin dan Tata Udara</option>
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
