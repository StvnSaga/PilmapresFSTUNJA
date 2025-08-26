@props(['status'])

@php
    $badgeClass = '';
    $text = ucfirst($status);

    switch ($status) {
        case 'diverifikasi':
            $badgeClass = 'bg-success';
            $text = 'Terverifikasi';
            break;
        case 'menunggu':
            $badgeClass = 'bg-warning';
            break;
        case 'ditolak':
            $badgeClass = 'bg-danger';
            break;
        default:
            $badgeClass = 'bg-secondary';
            break;
    }
@endphp

<span class="badge {{ $badgeClass }} mb-2">{{ $text }}</span>