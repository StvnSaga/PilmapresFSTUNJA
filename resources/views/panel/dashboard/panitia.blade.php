@extends('layouts.panel')

@section('title', 'Dashboard Panitia')

@section('content')
    <div class="page-heading">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Dashboard Panitia</h3>
                <p class="text-subtitle text-muted">Ringkasan data dan statistik penting.</p>
            </div>
            <div class="col-md-6 text-md-end">
                {{-- Tombol untuk menampilkan tahun seleksi yang sedang aktif --}}
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">
                        Tahun Seleksi : {{ $periodeAktif->tahun ?? 'Belum Ditetapkan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        @include('partials.panel._notification')

        {{-- Tampilkan konten dashboard hanya jika ada periode yang aktif --}}
        @if ($periodeAktif)
        
        <!-- BARIS 1: STATISTIK UTAMA -->
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

        <!-- BARIS 2: STATISTIK PROGRES PENILAIAN -->
        <section class="row">
            <div class="col-12 col-lg-4 col-md-6">
                <x-statistik-card color="success" icon="iconly-boldStar" title="Progres Penilaian CU" value="{{ $statistik['progresCu'] ?? 0 }} Peserta Dinilai" />
            </div>
            <div class="col-12 col-lg-4 col-md-6">
                <x-statistik-card color="success" icon="iconly-boldWork" title="Progres Penilaian GK" value="{{ $statistik['progresGk'] ?? '0 dari 0' }} Selesai" />
            </div>
            <div class="col-12 col-lg-4 col-md-6">
                <x-statistik-card color="success" icon="iconly-boldPaper" title="Progres Penilaian BI" value="{{ $statistik['progresBi'] ?? '0 dari 0' }} Selesai" />
            </div>
        </section>

        <!-- BARIS 3: DAFTAR TUGAS & INFORMASI -->
        <section class="row">

            {{-- Kartu Tugas: Penilaian CU --}}
            <x-dashboard-task-card 
                title="Tugas: Penilaian CU"
                :collection="$pesertaPerluDinilaiCu"
                buttonText="Nilai CU"
                buttonClass="btn-info"
                routeName="panel.penilaian.capaian-unggulan"
                routeParamName="peserta_id">
                
                <x-slot name="empty">
                    <li class="list-group-item text-center text-muted">
                        @if($statistik['jumlahTerverifikasi'] == 0)
                            Belum ada peserta terverifikasi.
                        @else
                            <i class="bi bi-check-circle-fill text-success"></i> Semua CU telah dinilai.
                        @endif
                    </li>
                </x-slot>
            </x-dashboard-task-card>

            {{-- Kartu Tugas: Siap Diverifikasi --}}
            <x-dashboard-task-card 
                title="Tugas: Siap Diverifikasi"
                :collection="$pesertaSiapVerifikasi"
                buttonText="Verifikasi"
                buttonClass="btn-success"
                routeName="panel.peserta.show">
                
                {{-- Ini adalah cara mengisi slot 'empty' dengan konten custom --}}
                <x-slot name="empty">
                    <li class="list-group-item text-center text-muted">
                        @if($statistik['jumlahPeserta'] == 0)
                            Belum ada peserta di periode ini.
                        @else
                            <i class="bi bi-check-circle-fill text-success"></i> Tidak ada tugas verifikasi.
                        @endif
                    </li>
                </x-slot>
            </x-dashboard-task-card>

            {{-- Kartu Info: Berkas Belum Lengkap --}}
            <x-dashboard-task-card 
                title="Info: Berkas Belum Lengkap"
                :collection="$pesertaBelumLengkapBerkas"
                buttonText="Lengkapi"
                buttonClass="btn-secondary"
                routeName="panel.peserta.show">

                <x-slot name="empty">
                    <li class="list-group-item text-center text-muted">
                        @if($statistik['jumlahPeserta'] == 0)
                            Belum ada peserta di periode ini.
                        @else
                            <i class="bi bi-check-circle-fill text-success"></i> Semua peserta sudah lengkap berkasnya.
                        @endif
                    </li>
                </x-slot>
            </x-dashboard-task-card>

        </section>

        <!-- BARIS 4: VISUALISASI DATA (CHART) -->
        <section class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Visualisasi Rata-rata Skor per Komponen</h4>
                    </div>
                    <div class="card-body">
                        {{-- Elemen div ini akan menjadi target untuk render chart --}}
                        <div id="chart-skor-komponen"></div>
                    </div>
                </div>
            </div>
        </section>
        
        @else
            {{-- Tampilan jika tidak ada periode seleksi yang aktif --}}
            <div class="alert alert-warning">
                <h4 class="alert-heading">Tidak Ada Periode Aktif</h4>
                <p>Silakan aktifkan satu periode di halaman "Manajemen Tahun Seleksi" untuk dapat melihat data pada dashboard.</p>
            </div>
        @endif
    </div>
@endsection

{{-- Menambahkan script khusus untuk halaman ini --}}
@push('scripts')
    {{-- Hanya jalankan script jika ada periode aktif dan data chart tersedia --}}
    @if ($periodeAktif && !empty($chartData))
        {{-- Load library ApexCharts dari CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Opsi konfigurasi untuk chart
            var options = {
                series: [{
                    name: 'Rata-rata Skor',
                    // Mengambil data series dari controller yang di-encode ke JSON
                    data: {!! json_encode($chartData['series']) !!}
                }],
                chart: {
                    height: 350,
                    type: 'bar'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '50%',
                        distributed: true, // Memberi warna berbeda untuk setiap bar
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        // Format angka menjadi 2 desimal
                        return val.toFixed(2);
                    }
                },
                xaxis: {
                    // Mengambil data kategori (label) dari controller
                    categories: {!! json_encode($chartData['categories']) !!}
                },
                yaxis: {
                    title: {
                        text: 'Rata-rata Nilai (Skala 0-100)'
                    }
                },
                legend: {
                    show: false // Sembunyikan legenda karena sudah ada judul
                },
                title: {
                    text: 'Perbandingan Rata-rata Skor antar Komponen Penilaian',
                    align: 'center'
                }
            };

            // Inisialisasi dan render chart
            var chart = new ApexCharts(document.querySelector("#chart-skor-komponen"), options);
            chart.render();
        </script>
    @endif
@endpush
