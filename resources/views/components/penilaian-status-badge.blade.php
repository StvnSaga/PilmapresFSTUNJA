@props(['dinilai'])

@if ($dinilai)
    <span class="badge bg-light-success">Sudah Dinilai</span>
@else
    <span class="badge bg-light-danger">Belum Dinilai</span>
@endif
