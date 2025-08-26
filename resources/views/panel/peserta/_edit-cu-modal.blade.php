<div class="modal fade" id="editCuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Capaian Unggulan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-edit-cu" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_deskripsi_cu">Deskripsi Prestasi/Aktivitas</label>
                        <input type="text" class="form-control" id="edit_deskripsi_cu" name="nama_berkas_cu" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_file_cu">Ganti File (Opsional)</label>
                        <input type="file" class="form-control" name="file" id="edit_file_cu">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti file bukti.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>