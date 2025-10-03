@extends('layouts.panel')

@section('title', 'Dashboard Panitia')

@section('content')
    <div class="page-heading">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Dashboard Panitia</h3>
                <p class="text-subtitle text-muted">Ringkasan data dan statistik penting.</p>
            </div>
            <div class="col-md-6 col-12 d-flex justify-content-md-end">
                <div class="px-3 py-2 rounded-pill bg-light-primary">
                    <span class="text-muted fs-5">Tahun Seleksi:</span>
                    <strong class="text-primary fs-5">{{ $periodeAktif->tahun ?? 'Belum Ada' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        @include('partials.panel._notification')

        @if ($periodeAktif)
            <section class="row">
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="blue" icon="iconly-boldUser" title="Jumlah Juri" value="{{ $statistik['jumlahJuri'] ?? 0 }} Juri" />
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="purple" icon="iconly-boldProfile" title="Jumlah Peserta" value="{{ $statistik['jumlahPeserta'] ?? 0 }} Peserta" />
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="green" icon="iconly-boldScan" title="Peserta Terverifikasi" value="{{ $statistik['jumlahTerverifikasi'] ?? 0 }} Peserta" />
                </div>
            </section>

            <section class="row">
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="red" icon="iconly-boldStar" title="Progres Penilaian CU" value="{{ $statistik['progresCu'] ?? 0 }} Peserta Dinilai" />
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="blue" icon="iconly-boldWork" title="Progres Penilaian GK" value="{{ $statistik['progresGk'] ?? '0 dari 0' }} Selesai" />
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <x-statistik-card color="green" icon="iconly-boldPaper" title="Progres Penilaian BI" value="{{ $statistik['progresBi'] ?? '0 dari 0' }} Selesai" />
                </div>
            </section>

            <section class="row">
                <x-dashboard-task-card title="Tugas: Penilaian CU" :collection="$pesertaPerluDinilaiCu" buttonText="Nilai CU" buttonClass="btn-info" routeName="panel.penilaian.capaian-unggulan" routeParamName="peserta_id">
                    <x-slot name="empty">
                        <li class="list-group-item text-center text-muted">
                            @if ($statistik['jumlahTerverifikasi'] == 0)
                                Belum ada peserta terverifikasi.
                            @else
                                <i class="bi bi-check-circle-fill text-success"></i> Semua CU telah dinilai.
                            @endif
                        </li>
                    </x-slot>
                </x-dashboard-task-card>

                <x-dashboard-task-card title="Tugas: Siap Diverifikasi" :collection="$pesertaSiapVerifikasi" buttonText="Verifikasi" buttonClass="btn-success" routeName="panel.peserta.show">
                    <x-slot name="empty">
                        <li class="list-group-item text-center text-muted">
                            @if ($statistik['jumlahPeserta'] == 0)
                                Belum ada peserta di periode ini.
                            @else
                                <i class="bi bi-check-circle-fill text-success"></i> Tidak ada tugas verifikasi.
                            @endif
                        </li>
                    </x-slot>
                </x-dashboard-task-card>

                <x-dashboard-task-card title="Info: Berkas Belum Lengkap" :collection="$pesertaBelumLengkapBerkas" buttonText="Lengkapi" buttonClass="btn-secondary" routeName="panel.peserta.show">
                    <x-slot name="empty">
                        <li class="list-group-item text-center text-muted">
                            @if ($statistik['jumlahPeserta'] == 0)
                                Belum ada peserta di periode ini.
                            @else
                                <i class="bi bi-check-circle-fill text-success"></i> Semua peserta sudah lengkap berkasnya.
                            @endif
                        </li>
                    </x-slot>
                </x-dashboard-task-card>
            </section>

            <section class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Visualisasi Rata-rata Skor per Komponen</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-skor-komponen"></div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <div class="alert alert-warning">
                <h4 class="alert-heading">Tidak Ada Periode Aktif</h4>
                <p>Silakan aktifkan satu periode di halaman "Manajemen Tahun Seleksi" untuk dapat melihat data pada dashboard.</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    @if ($periodeAktif && !empty($chartData))
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Inisialisasi chart rata-rata skor per komponen.
            var options = {
                series: [{
                    name: 'Rata-rata Skor',
                    data: {!! json_encode($chartData['series']) !!}
                }],
                chart: { height: 350, type: 'bar' },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '50%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) { return val.toFixed(2); }
                },
                xaxis: {
                    categories: {!! json_encode($chartData['categories']) !!}
                },
                yaxis: {
                    title: { text: 'Rata-rata Nilai (Skala 0-100)' }
                },
                legend: { show: false },
                title: {
                    text: 'Perbandingan Rata-rata Skor antar Komponen Penilaian',
                    align: 'center'
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart-skor-komponen"), options);
            chart.render();
        </script>
    @endif
@endpush