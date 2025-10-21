@props([
    'title',
    'collection',
    'buttonText',
    'buttonClass',
    'routeName',
    'routeParamName' => 'peserta'
])

<div class="col-12 col-lg-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="card-content pb-4">
            <div style="max-height: 190px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @forelse ($collection as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $item->nama_lengkap }}</p>
                                <small class="text-muted">{{ $item->nim }}</small>
                            </div>
                            <a href="{{ route($routeName, [$routeParamName => $item->id]) }}" class="btn btn-sm {{ $buttonClass }}">
                                {{ $buttonText }}
                            </a>
                        </li>
                    @empty
                        {{ $empty }}
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
