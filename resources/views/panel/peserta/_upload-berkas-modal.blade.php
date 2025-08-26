<div class="modal fade" id="uploadBerkasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="uploadBerkasModalLabel">Upload Berkas</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="form-upload-berkas" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="upload_jenis_berkas" name="jenis_berkas">
                    <input type="hidden" id="upload_nama_berkas_wajib" name="nama_berkas">
                    
                    {{-- Bagian ini hanya untuk CU --}}
                    <div id="cu-fields" style="display: none;">
                        <div class="form-group">
                            <label for="upload_deskripsi_cu">Deskripsi Prestasi/Aktivitas</label>
                            <input type="text" class="form-control" id="upload_deskripsi_cu" name="nama_berkas_cu" required>
                        </div>
                        
                        {{-- !! BLOK "TINGKAT" DIHAPUS DARI SINI !! --}}

                    </div>
                    
                    <div class="form-group">
                        <label for="file">Pilih File (PDF, JPG, PNG - Maks 2MB)</label>
                        <input type="file" class="form-control" name="file" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Berkas</button></div>
            </form>
        </div>
    </div>
</div>
