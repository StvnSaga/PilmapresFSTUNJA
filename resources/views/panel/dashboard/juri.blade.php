@extends('layouts.panel')

@section('title', 'Dashboard Juri')

@section('content')
    <div class="page-heading">
        <div class="row align-items-center gy-3">
            <div class="col-md-8 col-12">
                <h3>Dashboard Juri</h3>
                <p class="text-subtitle text-muted mb-0">
                    Selamat datang, {{ auth()->user()->name }}. Ringkasan data dan statistik penting.
                </p>
            </div>
            <div class="col-md-4 col-12 d-flex justify-content-md-end">
                <div class="px-3 py-2 rounded-pill bg-light-primary">
                    <span class="text-muted fs-5">Tahun Seleksi:</span>
                    <strong class="text-primary fs-5">{{ $periodeAktif->tahun ?? 'Belum Ada' }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        {{-- !! LOGIKA BARU: Tampilkan peringatan jika penilaian terkunci !! --}}
        @if ($penilaianLocked)
            <div class="alert alert-warning">
                <h4 class="alert-heading">Tahap Penilaian Belum Dimulai</h4>
                <p>Admin belum membuka tahap penilaian untuk periode ini. Anda akan dapat melihat daftar tugas setelah tahap penilaian dimulai.</p>
            </div>
        @else
            {{-- Jika tidak terkunci, tampilkan konten seperti biasa --}}
            <section class="row">
                {{-- Kartu Statistik --}}
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="red" icon="iconly-boldTime-Circle" title="Perlu Dinilai" value="{{ $statistik['perluDinilai'] ?? 0 }}" />
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="green" icon="iconly-boldShield-Done" title="Selesai Dinilai" value="{{ $statistik['selesaiDinilai'] ?? 0 }}" />
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="purple" icon="iconly-boldUser" title="Total Peserta" value="{{ $statistik['totalPeserta'] ?? 0 }}" />
                </div>
            </section>
            
            <section class="row">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Tugas Penilaian</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-lg">
                                <thead>
                                    <tr>
                                        <th>Nama Peserta</th>
                                        <th>Program Studi</th>
                                        {{-- PERUBAHAN: Tidak ada lagi kolom "Aksi" --}}
                                        <th class="text-center">Gagasan Kreatif (GK)</th>
                                        <th class="text-center">Bahasa Inggris (BI)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pesertas as $peserta)
                                        @php
                                            $penilaian = $peserta->penilaians->where('juri_id', auth()->id())->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $peserta->nama_lengkap }}</td>
                                            <td>{{ $peserta->prodi }}</td>

                                            {{-- PERUBAHAN: Memanggil komponen baru di dalam kolom status --}}
                                            <td class="text-center">
                                                @if ($penilaian)
                                                    <x-penilaian-status-action :dinilai="$penilaian->sudah_dinilai_gk" :url="route('juri.penilaian.gk', [
                                                        'peserta_id' => $peserta->id,
                                                    ])"
                                                        label="GK" buttonClass="btn-primary" />
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($penilaian)
                                                    <x-penilaian-status-action :dinilai="$penilaian->sudah_dinilai_bi" :url="route('juri.penilaian.bi', [
                                                        'peserta_id' => $peserta->id,
                                                    ])"
                                                        label="BI" buttonClass="btn-info" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada peserta yang perlu dinilai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Progres Penilaian</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-progress-penilaian"></div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
@endsection

@push('scripts')
    @if (($statistik['totalPeserta'] ?? 0) > 0)
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            var options = {
                series: [{{ $statistik['selesaiDinilai'] ?? 0 }}, {{ $statistik['perluDinilai'] ?? 0 }}],
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: ['Selesai Dinilai', 'Perlu Dinilai'],
                colors: ['#435ebe', '#dc3545'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#chart-progress-penilaian"), options);
            chart.render();
        </script>
    @endif
@endpush
