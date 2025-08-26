{{-- Pastikan ID modal sudah benar: "batalVerifikasiModal" --}}
<div class="modal fade" id="batalVerifikasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white">Konfirmasi Pembatalan Verifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan verifikasi untuk <strong>{{ $peserta->nama_lengkap }}</strong>?</p>
                <p class="text-danger fw-semibold">Tindakan ini akan mengubah status kembali menjadi "Menunggu" dan menghapus semua data penilaian yang mungkin sudah diberikan oleh juri.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <form action="{{ route('panel.peserta.unverify', $peserta->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning">Ya, Batalkan Verifikasi</button>
                </form>
            </div>
        </div>
    </div>
</div>
