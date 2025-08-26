@extends('layouts.panel')

@section('title', 'Rekapitulasi Nilai')

@section('content')
    <div class="page-heading">
        <h3>Rekapitulasi Nilai</h3>
        <p class="text-subtitle text-muted">Ringkasan total nilai dari semua peserta.</p>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Tabel Rekapitulasi Nilai Peserta</h4>
                <div class="btn-group">
                    <a href="{{ route('panel.laporan.export-excel') }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                    </a>
                    <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('panel.laporan.export-zip') }}">
                                <i class="bi bi-file-earmark-zip-fill"></i> Export Arsip Lengkap (.ZIP)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-light-info">
                    <i class="bi bi-info-circle"></i> Informasi: Nilai Akhir dihitung berdasarkan bobot: <strong>CU (45%)</strong>, <strong>GK (35%)</strong>, dan <strong>BI (20%)</strong>.
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Peserta</th>
                                <th>NIM</th>
                                <th>Nilai CU</th>
                                <th>Nilai GK</th>
                                <th>Nilai BI</th>
                                <th>Nilai Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesertas as $peserta)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td>{{ $peserta->nama_lengkap }}</td>
                                    <td>{{ $peserta->nim }}</td>
                                    <td>{{ number_format($peserta->total_skor_cu, 2) }}</td>
                                    <td>{{ number_format($peserta->total_skor_gk, 2) }}</td>
                                    <td>{{ number_format($peserta->total_skor_bi, 2) }}</td>
                                    <td><span class="badge bg-primary">{{ number_format($peserta->skor_akhir, 2) }}</span></td>
                                    <td>
                                        {{-- Ganti <a> di dalam <td> Aksi --}}
                                        <a href="{{ route('panel.laporan.detail-rekap', ['peserta' => $peserta->id, 'peringkat' => $loop->iteration]) }}" class="btn btn-sm btn-info">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data penilaian untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
