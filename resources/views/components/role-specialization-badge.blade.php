@props(['role', 'specialization' => null])

<div class="d-flex align-items-center gap-1">
    {{-- Badge Utama untuk Role --}}
    @if ($role === 'admin')
        <span class="badge bg-light-danger">Admin</span>
    @elseif ($role === 'panitia')
        <span class="badge bg-light-info">Panitia</span>
    @elseif ($role === 'juri')
        <span class="badge bg-light-success">Juri</span>
    @endif

    {{-- Badge Tambahan untuk Spesialisasi Juri --}}
    @if ($role === 'juri' && $specialization)
        <span class="badge bg-light-secondary">{{ $specialization }}</span>
    @endif
</div>