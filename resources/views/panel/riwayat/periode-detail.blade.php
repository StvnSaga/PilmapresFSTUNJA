@extends('layouts.panel')

@section('title', 'Detail Riwayat Pilmapres ' . $periode->tahun)

@section('content')
    <div class="page-heading">
        {{-- ... (Bagian judul & breadcrumb tidak berubah) ... --}}
    </div>

    <div class="page-content">
        <section class="section">
            {{-- Kartu Pemenang --}}
            @if ($pemenang)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl me-3">
                                <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar Pemenang">
                                <span class="avatar-status bg-success"></span>
                            </div>
                            <div>
                                <h5 class="mb-0">PEMENANG PILMAPRES {{ $periode->tahun }}</h5>
                                <h3 class="font-bold">{{ $pemenang->nama_lengkap }}</h3>
                                <p class="text-muted mb-0">{{ $pemenang->prodi }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                             <div class="col-md-12">
                                <h6>Komposisi Nilai</h6>
                                {{-- PERUBAHAN: Menggunakan komponen baru dan accessor dari Model --}}
                                <x-score-breakdown-bar label="Capaian Unggulan (45%)" :weightedScore="$pemenang->nilai_cu_berbobot" maxWeightedScore="45" />
                                <x-score-breakdown-bar label="Gagasan Kreatif (35%)" :weightedScore="$pemenang->nilai_gk_berbobot" maxWeightedScore="35" />
                                <x-score-breakdown-bar label="Bahasa Inggris (20%)" :weightedScore="$pemenang->nilai_bi_berbobot" maxWeightedScore="20" />
                             </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h5 class="mb-0">Total Skor Akhir</h5>
                            <h2 class="font-extrabold text-primary">{{ number_format($pemenang->skor_akhir, 2) }}</h2>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tabel Peringkat Peserta (tidak berubah, sudah cukup baik) --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Peringkat Peserta</h4>
                    @php
                        $role = auth()->user()->role;
                        $exportExcelRoute = ($role == 'admin' ? 'admin.riwayat.export-excel' : 'panitia.riwayat.export-excel');
                        $exportZipRoute = ($role == 'admin' ? 'admin.riwayat.export-zip' : 'panitia.riwayat.export-zip');
                    @endphp
                    <div class="btn-group">
                        <a href="{{ route($exportExcelRoute, $periode->tahun) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel-fill"></i> Export Excel</a>
                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"><span class="visually-hidden">Toggle Dropdown</span></button>
                        <ul class="dropdown-menu"><li><a class="dropdown-item" href="{{ route($exportZipRoute, $periode->tahun) }}"><i class="bi bi-file-earmark-zip-fill"></i> Export Arsip Lengkap (.ZIP)</a></li></ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Peserta</th>
                                    <th>Skor Akhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesertas as $peserta)
                                    <tr>
                                        <td><strong>{{ $loop->iteration }}</strong></td>
                                        <td>{{ $peserta->nama_lengkap }}</td>
                                        <td>{{ number_format($peserta->skor_akhir, 2) }}</td>
                                        <td>
                                            {{-- !! PERBAIKAN UTAMA ADA DI SINI !! --}}
                                            @php
                                                $detailRouteName = (auth()->user()->role == 'admin') ? 'admin.laporan.detail-rekap' : 'panel.laporan.detail-rekap';
                                            @endphp
                                            <a href="{{ route($detailRouteName, ['peserta' => $peserta->id, 'peringkat' => $loop->iteration]) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">Belum ada data peserta pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection