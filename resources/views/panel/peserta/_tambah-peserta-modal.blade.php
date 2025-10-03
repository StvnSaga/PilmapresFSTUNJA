<div class="modal fade" id="tambahPesertaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Peserta Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('panel.peserta.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('panel.peserta._peserta-form-fields', ['prefix' => 'tambah', 'prodiList' => $prodiList])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
