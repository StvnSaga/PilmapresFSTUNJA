@props(['dinilai', 'url', 'label', 'buttonClass'])

@if ($dinilai)
    {{-- Jika sudah dinilai, tampilkan badge yang bisa diklik untuk melihat nilai --}}
    <a href="{{ $url }}" class="badge bg-light-success">Sudah Dinilai</a>
@else
    {{-- Jika belum dinilai, tampilkan tombol untuk memberi nilai --}}
    <a href="{{ $url }}" class="btn btn-sm {{ $buttonClass }}">Nilai {{ $label }}</a>
@endif
