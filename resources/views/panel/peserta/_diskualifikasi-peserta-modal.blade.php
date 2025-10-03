<div class="modal fade" id="tolakPendaftaranModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    {{ $periodeAktif->status == 'pendaftaran' ? 'Konfirmasi Tolak Pendaftaran' : 'Konfirmasi Diskualifikasi Peserta' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menolak pendaftaran untuk <strong>{{ $peserta->nama_lengkap }}</strong>?</p>
                <p class="text-danger fw-semibold">
                    Tindakan ini akan mengubah status peserta menjadi "Ditolak" dan menghapus semua data penilaian yang terkait.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('panel.peserta.disqualify', $peserta->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">
                        {{ $periodeAktif->status == 'pendaftaran' ? 'Ya, Tolak Pendaftaran' : 'Ya, Diskualifikasi Peserta' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>