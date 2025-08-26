@props(['role'])

@php
    $badgeClass = '';
    switch ($role) {
        case 'admin':
            $badgeClass = 'bg-primary';
            break;
        case 'panitia':
            $badgeClass = 'bg-info';
            break;
        case 'juri':
            $badgeClass = 'bg-secondary';
            break;
        default:
            $badgeClass = 'bg-light-secondary';
            break;
    }
@endphp

<span class="badge {{ $badgeClass }}">{{ ucfirst($role) }}</span>