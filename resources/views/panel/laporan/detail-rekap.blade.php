@extends('layouts.panel')

@section('title', 'Detail Rekapitulasi: ' . $peserta->nama_lengkap)

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $peserta->nama_lengkap }}</h3>
                    <p class="text-subtitle text-muted">{{ $peserta->nim }} | {{ $peserta->prodi }}</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.laporan.rekap-nilai') }}">Rekapitulasi Nilai</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Ringkasan Nilai Akhir</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <h1 class="display-4 text-primary">{{ number_format($peserta->skor_akhir, 2) }}</h1>
                        <p class="text-muted mb-0">Nilai Akhir</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h1 class="display-4">{{ $peringkat }}</h1>
                        <p class="text-muted mb-0">Peringkat</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Skor Komponen (setelah bobot):</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Nilai CU (45%)</span>
                                <strong>{{ number_format($peserta->nilai_cu_berbobot, 2) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Nilai GK (35%)</span>
                                <strong>{{ number_format($peserta->nilai_gk_berbobot, 2) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Nilai BI (20%)</span>
                                <strong>{{ number_format($peserta->nilai_bi_berbobot, 2) }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Detail Nilai Capaian Unggulan (CU)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Prestasi/Kegiatan</th>
                                        <th>Tingkat</th>
                                        <th>Wujud</th>
                                        <th>Skor</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($berkasCu as $berkas)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $berkas->nama_berkas }}</td>
                                            <td>{{ $berkas->tingkat }}</td>
                                            <td>{{ $berkas->cu_wujud }}</td>
                                            <td>{{ $berkas->skor }}</td>
                                            <td>
                                                {{-- PERUBAHAN DI SINI: Mengganti class 'btn-info' menjadi 'btn-outline-primary' --}}
                                                <a href="{{ asset('storage/' . $berkas->path_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Berkas</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data Capaian Unggulan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total Skor CU (sebelum bobot):</th>
                                        <th>{{ number_format($peserta->total_skor_cu, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <x-penilaian-detail-card 
                title="Detail Nilai Gagasan Kreatif (GK)"
                :berkas="$berkasGk"
                :penilaians="$peserta->penilaians"
                :kriteriaMap="$kriteriaGkMap"
                skorType="total_skor_gk"
                detailType="skor_gk_detail"
                catatanType="catatan_juri_gk"
                catatanDetailType="catatan_gk_detail"
                :rataRataSkor="$peserta->total_skor_gk"
                accordionId="accordionGk"
            />

            <x-penilaian-detail-card 
                title="Detail Nilai Bahasa Inggris (BI)"
                :berkas="$berkasSlideGk"
                :penilaians="$peserta->penilaians"
                :kriteriaMap="$kriteriaBiMap"
                skorType="total_skor_bi"
                detailType="skor_bi_detail"
                catatanType="catatan_juri_bi"
                catatanDetailType="catatan_bi_detail"
                :rataRataSkor="$peserta->total_skor_bi"
                accordionId="accordionBi"
            />
        </div>
    </div>
@endsection
