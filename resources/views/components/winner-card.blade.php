@props(['pemenang'])

@php
    $foto_rekam_jejak = $pemenang->foto_path;
    $foto_peserta = $pemenang->peserta->foto_path;
    $foto_final = $foto_rekam_jejak ?? $foto_peserta;
@endphp

<div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100">
        <div class="p-3">
            <img src="{{ $foto_final ? asset('storage/' . $foto_final) : asset('images/default-avatar.png') }}" 
                 class="img-fluid rounded-2 winner-card-image" 
                 alt="{{ $pemenang->peserta->nama_lengkap }}">
        </div>
        <div class="card-body d-flex flex-column pt-0">
            <h5 class="card-title fw-bold">{{ $pemenang->peserta->nama_lengkap }}</h5>
            <p class="card-text mb-2"><small class="text-primary fw-semibold">Peringkat {{ $pemenang->peringkat }}</small></p>
            <p class="card-text small text-muted">{{ $pemenang->deskripsi_singkat ?? 'Peserta berprestasi dari Fakultas Sains dan Teknologi.' }}</p>
            <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="winner-card-major">{{ $pemenang->peserta->prodi }}</span>
                <div>
                    <a href="#" class="text-secondary text-decoration-none ms-2"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-secondary text-decoration-none ms-2"><i class="bi bi-linkedin fs-5"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
