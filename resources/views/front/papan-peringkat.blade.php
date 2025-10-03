@extends('layouts.page')

@section('title', 'Papan Peringkat')

@section('content')
<div class="leaderboard-wrapper">
    <div class="leaderboard-header-bg"></div>

    <div class="container leaderboard-container">
        <div class="card border-0 shadow-lg leaderboard-card">
            <div class="card-body p-lg-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bolder">Papan Peringkat Pilmapres FST</h2>
                    <p class="h4 text-muted fw-semibold mb-4">Periode Terkini</p>
                    <p class="text-muted">Peringkat diperbarui secara berkala.</p>
                </div>

                @if($top3->count() >= 3)
                    <div class="row justify-content-center align-items-end text-center mb-5">
                        <x-podium-card :peserta="$top3[1]" :rank="$top3[1]->rank ?? 2" />
                        <x-podium-card :peserta="$top3[0]" :rank="$top3[0]->rank ?? 1" />
                        <x-podium-card :peserta="$top3[2]" :rank="$top3[2]->rank ?? 3" />
                    </div>
                @elseif($pesertas->isNotEmpty())
                    <p class="text-center text-muted">Data peringkat belum cukup untuk menampilkan podium.</p>
                @endif
                
                <hr>
                <h4 class="text-center my-4 fw-bold">Peringkat Keseluruhan</h4>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <tbody>
                            @forelse($others as $index => $peserta)
                                <x-leaderboard-row :peserta="$peserta" :rank="$index + 4" />
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">
                                        @if($pesertas->isEmpty())
                                            Belum ada data peserta yang bisa ditampilkan.
                                        @else
                                            Hanya ada data untuk podium saat ini.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection