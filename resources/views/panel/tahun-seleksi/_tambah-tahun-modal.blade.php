<div class="modal fade" id="tambahTahunModal" tabindex="-1" aria-labelledby="tambahTahunModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahTahunModalLabel">Tambah Tahun Seleksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-tambah-tahun" method="POST" action="{{ route('admin.tahun-seleksi.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" class="form-control @error('tahun') is-invalid @enderror" id="tahun"
                            name="tahun" placeholder="Contoh: {{ $tahunPlaceholder }}" min="2020" max="2099"
                            required>
                        <small class="text-muted">Masukkan tahun pelaksanaan {{ $tahunPlaceholder }}.</small>

                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="form-tambah-tahun">
                        <i class="bi bi-check-circle"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
