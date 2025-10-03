@props([
    'title',
    'berkas',
    'penilaians',
    'kriteriaMap',
    'skorType',
    'detailType',
    'catatanType',
    'catatanDetailType', 
    'rataRataSkor',
    'accordionId',
])

<div class="col-md-6">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            @if ($berkas)
                <a href="{{ asset('storage/' . $berkas->path_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    Lihat Berkas
                </a>
            @endif
        </div>
        <div class="card-body">
            <div class="accordion" id="{{ $accordionId }}">
                @forelse($penilaians as $penilaian)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $accordionId }}-{{ $penilaian->id }}">
                                Skor dari Juri: <strong>{{ $penilaian->juri->name }}</strong>&nbsp; (Total: {{ number_format($penilaian->$skorType, 2) }})
                            </button>
                        </h2>
                        <div id="collapse-{{ $accordionId }}-{{ $penilaian->id }}" class="accordion-collapse collapse" data-bs-parent="#{{ $accordionId }}">
                            <div class="accordion-body">
                                @if ($penilaian->$detailType)
                                    <ul class="list-group list-group-flush">
                                        @foreach($penilaian->$detailType as $key => $skor)

                                            <li class="list-group-item d-flex justify-content-between ps-0">
                                                <span>{{ $kriteriaMap[$key] ?? $key }}</span>
                                                <span class="badge bg-light-secondary">{{ $skor }}</span>
                                            </li>
                                            
                                            @if(isset($penilaian->$catatanDetailType[$key]))
                                                <li class="list-group-item pt-0 pb-2 ps-2 border-0">
                                                    <small class="text-muted fst-italic">
                                                        <i class="bi bi-chat-right-quote me-1"></i>
                                                        {{ $penilaian->$catatanDetailType[$key] }}
                                                    </small>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                                
                                @if ($penilaian->$catatanType)
                                    <hr class="my-2">
                                    <p class="text-muted fst-italic mt-3">
                                        <strong>Catatan Umum:</strong> "{{ $penilaian->$catatanType }}"
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada penilaian dari juri.</p>
                @endforelse
            </div>
            <hr>
            <h5 class="text-end">Rata-rata Skor: <strong>{{ number_format($rataRataSkor, 2) }}</strong></h5>
        </div>
    </div>
</div>
