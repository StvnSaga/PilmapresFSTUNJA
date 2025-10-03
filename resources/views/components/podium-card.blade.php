@props(['peserta', 'rank'])

@php
    $isWinner = ($rank == 1);
    $columnClass = $isWinner ? 'col-lg-4' : 'col-lg-3';
    $imageClass = 'podium-image' . ($isWinner ? ' rank-1' : '');
    $scoreboxClass = 'podium-scorebox' . ($isWinner ? ' rank-1' : '');
@endphp

<div class="{{ $columnClass }} col-md-6 mb-4 text-center">
    <img src="{{ $peserta->foto_path ? asset('storage/' . $peserta->foto_path) : asset('images/default-avatar.png') }}" alt="{{ $peserta->nama_lengkap }}" class="rounded-circle mb-3 {{ $imageClass }}">
    
    @if($isWinner)
    <h4 class="fw-bolder mb-1">{{ $peserta->nama_lengkap }}</h4>
    <p class="text-muted">{{ $peserta->nim }}</p>
    <p class="fw-bold text-warning">Peringkat {{ $rank }} 🥇</p>
    @else
        <h5 class="fw-bold mb-1">{{ $peserta->nama_lengkap }}</h5>
            <p class="text-muted">{{ $peserta->nim }}</p>
        <p class="text-muted">Peringkat {{ $rank }}</p>
    @endif
    
    <div class="p-3 rounded {{ $scoreboxClass }}">
        @if($isWinner)
            <h2 class="fw-bolder text-warning-emphasis mb-0">{{ number_format($peserta->skor_akhir, 2) }}</h2>
        @else
            <h3 class="fw-bold text-secondary-emphasis mb-0">{{ number_format($peserta->skor_akhir, 2) }}</h3>
        @endif
    </div>
</div>
