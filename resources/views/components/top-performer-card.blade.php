@props(['peserta'])

<a href="{{ route('panel.laporan.detail-rekap', ['peserta' => $peserta, 'peringkat' => 1]) }}"
   class="card top-performer-card rounded-4 text-decoration-none">
    <div class="card-body py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-trophy-fill text-warning top-performer-icon"></i>
                </div>
                <div>
                    <h6 class="text-white-50 mb-1">Saat Ini Memimpin Peringkat</h6>
                    <h4 class="text-white font-extrabold mb-0">{{ $peserta->nama_lengkap }}</h4>
                    <small class="text-white-50">{{ $peserta->nim }} &bull; {{ $peserta->prodi }}</small>
                </div>
            </div>
            <div class="text-end">
                <h6 class="text-white-50 mb-1">Total Skor</h6>
                <h3 class="text-white font-extrabold mb-0">{{ number_format($peserta->skor_akhir, 2) }}</h3>
            </div>
        </div>
    </div>
</a>