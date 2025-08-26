@props(['rank'])

<span class="fs-5">
    @if ($rank == 1)
        <i class="bi bi-trophy-fill text-warning" title="Peringkat 1"></i>
    @elseif ($rank == 2)
        <i class="bi bi-trophy-fill text-secondary" title="Peringkat 2"></i>
    @elseif ($rank == 3)
        <i class="bi bi-trophy-fill icon-bronze" title="Peringkat 3"></i>
    @else
        <strong>{{ $rank }}</strong>
    @endif
</span>