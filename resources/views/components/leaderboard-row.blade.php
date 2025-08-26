@props(['peserta', 'rank'])

<tr>
    <td class="text-center leaderboard-table-rank">
        <h4 class="text-muted fw-bold">{{ $rank }}</h4>
    </td>
    <td>
        <div class="d-flex align-items-center">
            {{-- !! PERUBAHAN DI SINI !! --}}
            <img src="{{ $peserta->foto_path ? asset('storage/' . $peserta->foto_path) : asset('images/default-avatar.png') }}" alt="{{ $peserta->nama_lengkap }}" class="rounded-circle me-3 leaderboard-table-avatar">
            <div>
                <h6 class="fw-bold mb-0">{{ $peserta->nama_lengkap }}</h6>
                <p class="text-muted mb-0 small">{{ $peserta->nim }}</p>
            </div>
        </div>
    </td>
    <td class="text-end">
        <h5 class="mb-0 fw-semibold">{{ number_format($peserta->skor_akhir, 2) }}</h5>
    </td>
</tr>
