@props(['item'])

@php
    $role = auth()->user()->role;

    $routeName = $role . '.riwayat.detail';
@endphp

<div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100 year-list-item">
        <div class="card-body text-center d-flex flex-column">
            <div class="mb-3">
                <i class="bi bi-calendar-check-fill fs-1 text-primary"></i>
            </div>
            <h4>Pilmapres FST {{ $item['tahun'] }}</h4>
            <p class="text-muted mt-auto">Pemenang: <strong>{{ $item['pemenang'] }}</strong></p>
            
            {{-- 3. Gunakan nama rute yang dinamis di sini --}}
            <a href="{{ route($routeName, ['tahun' => $item['tahun']]) }}" class="btn btn-outline-primary">Lihat Detail</a>
        </div>
    </div>
</div>
