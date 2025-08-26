    {{-- Modal ini akan dipanggil untuk setiap periode yang statusnya 'penilaian' --}}
    <div class="modal fade" id="akhirPeriodeModal{{ $periode->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Konfirmasi Akhiri Periode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengakhiri dan mengarsipkan periode <strong>{{ $periode->tahun }}</strong>?</p>
                    <p class="text-danger fw-semibold">Tindakan ini akan mengunci semua data dan menonaktifkan periode ini. Anda tidak akan bisa mengubah data apa pun lagi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.tahun-seleksi.endPeriod', $periode->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Ya, Akhiri Periode</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    