<div class="modal fade" id="bayarSPP{{ $s->nisn }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/pembayaran/bayar/{{ $s->nisn }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Ubah Siswa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="col-form-label">NIS</label>
                        <input type="text" class="form-control" name="nis" value="{{ $s->nis }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" value="{{ $s->nama }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Kelas</label>
                        <input type="text" class="form-control" name="kelas"
                            value="{{ $s->kelas->nama_kelas }} {{ $s->kelas->kompetensi_keahlian }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">SPP</label>
                        <input type="text" class="form-control"
                            value="{{ 'Rp ' . number_format($s->spp->nominal, '0', ',', '.') }}" disabled>
                        <input type="hidden" id="nominalSPP{{ $s->nisn }}" value="{{ $s->spp->nominal }}">
                    </div>
                    @php
                        $paidMonths = $pembayaran->where('nisn', $s->nisn)->pluck('bulan_dibayar')->toArray();
                    @endphp

                    <div class="mb-3">
                        <label class="col-form-label">Bulan Yang Akan Bayar</label>
                        <select name="bulan_dibayar[]" class="form-select mb-1" id="bulanSelect{{ $s->nisn }}" multiple>
                            @foreach ($bulan as $b)
                                @if (!in_array($b['bulan'], $paidMonths))
                                    <option value="{{ $b['bulan'] }}">
                                        {{ $b['bulan'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <small style="color: #999; font-size: 0.8rem;">Tekan CTRL + click untuk memilih lebih dari satu.</small>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Jumlah Bayar</label>
                        <input type="number" class="form-control" id="jumlahBayar{{ $s->nisn }}" readonly>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bulanSelect = document.getElementById('bulanSelect{{ $s->nisn }}');
        const nominalSPP = parseInt(document.getElementById('nominalSPP{{ $s->nisn }}').value);
        const jumlahBayar = document.getElementById('jumlahBayar{{ $s->nisn }}');

        bulanSelect.addEventListener('change', function() {
            let selectedCount = Array.from(bulanSelect.selectedOptions).length;
            jumlahBayar.value = nominalSPP * selectedCount;
        });
    });
</script>
