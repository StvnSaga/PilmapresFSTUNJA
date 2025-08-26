@extends('layouts.panel')

@section('title', 'Live Ranking')

@section('content')
    <div class="page-heading">
        <h3>Live Ranking</h3>
        <p class="text-subtitle text-muted">Papan peringkat peserta berdasarkan nilai saat ini.</p>
    </div>

    <div class="page-content">
        @if ($topPerformer)
            <x-top-performer-card :peserta="$topPerformer" />
        @endif

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tabel Peringkat</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover leaderboard-table">
                        <thead>
                            <tr>
                                <th class="col-rank text-center">Peringkat</th>
                                <th>Nama Peserta</th>
                                <th class="text-center">Nilai CU</th>
                                <th class="text-center">Nilai GK</th>
                                <th class="text-center">Nilai BI</th>
                                <th class="col-score">Total Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesertas as $peserta)
                                <tr>
                                    <td class="text-center">
                                        <x-rank-medal :rank="$loop->iteration" />
                                    </td>
                                    <td>
                                        <strong>{{ $peserta->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $peserta->nim }} &bull; {{ $peserta->prodi }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-primary">{{ number_format($peserta->total_skor_cu ?? 0, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-success">{{ number_format($peserta->total_skor_gk ?? 0, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-info">{{ number_format($peserta->total_skor_bi ?? 0, 2) }}</span>
                                    </td>
                                    <td>
                                        <x-score-progress-bar :score="$peserta->skor_akhir" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data penilaian untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection