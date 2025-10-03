@props([
    'title',
    'collection',
    'buttonText',
    'buttonClass' => 'btn-info', 
    'routeName',
    'routeParamName' => 'peserta', 
])

<div class="col-md-4">
    <div class="card h-100">
        <div class="card-header"><h4 class="card-title">{{ $title }}</h4></div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse ($collection as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{ $item->nama_lengkap }}</h6>
                            <small class="text-muted">{{ $item->nim }}</small>
                        </div>
                        <a href="{{ route($routeName, [$routeParamName => $item->id]) }}" class="btn btn-sm {{ $buttonClass }}">
                            {{ $buttonText }}
                        </a>
                    </li>
                @empty
                    {{-- Di sini kita sediakan "slot" untuk diisi pesan custom --}}
                    {{ $empty }}
                @endforelse
            </ul>
        </div>
    </div>
</div>