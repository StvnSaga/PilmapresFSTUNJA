@props(['status', 'berkasLengkap' => false])

@php
    $badgeClass = '';
    $text = '';

    switch ($status) {
        case 'menunggu':
            if ($berkasLengkap) {
                $badgeClass = 'bg-light-info';
                $text = 'Siap Diverifikasi';
            } else {
                $badgeClass = 'bg-light-warning';
                $text = 'Menunggu Berkas';
            }
            break;
        case 'diverifikasi':
            $badgeClass = 'bg-success';
            $text = 'Diverifikasi';
            break;
        case 'ditolak':
            $badgeClass = 'bg-danger';
            $text = 'Didiskualifikasi';
            break;
        default:
            $badgeClass = 'bg-secondary';
            $text = 'Tidak Diketahui';
            break;
    }
@endphp

<span class="badge {{ $badgeClass }} mb-2">{{ $text }}</span>