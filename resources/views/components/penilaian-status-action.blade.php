@props(['dinilai', 'url', 'label', 'buttonClass'])

@if ($dinilai)
    <a href="{{ $url }}" class="badge bg-light-success">Sudah Dinilai</a>
@else
    <a href="{{ $url }}" class="btn btn-sm {{ $buttonClass }}">Nilai {{ $label }}</a>
@endif
