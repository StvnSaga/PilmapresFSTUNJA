@props(['icon', 'color', 'title', 'value'])

{{-- Tambahkan kelas mb-3 untuk mengatur jarak bawah standar --}}
<div class="card mb-3">
    <div class="card-body px-4 py-4-5">
        <div class="d-flex align-items-center">
            <div class="stats-icon {{ $color }} d-flex align-items-center justify-content-center">
                <i class="{{ $icon }}" style="font-size: 2.2rem;"></i>
            </div>
            <div class="ms-3">
                <h6 class="text-muted font-semibold mb-0">{{ $title }}</h6>
                <h5 class="font-extrabold mb-0">{{ $value }}</h5>
            </div>
        </div>
    </div>
</div>