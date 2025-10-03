@extends('layouts.app')

@section('content')
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center pt-4">
                <div class="col-md-6 pb-5">
                    <h1 class="display-5 fw-semibold">
                        <strong>Pemilihan Mahasiswa <span class="hero-title-highlight">Berprestasi</span> Fakultas Sains dan Teknologi</strong>
                    </h1>
                    <p class="mt-4 fs-6 hero-text">
                        Ajang bergengsi bagi mahasiswa Fakultas Sains dan Teknologi yang memiliki prestasi akademik, karya inovatif, kepemimpinan, serta kontribusi nyata dalam masyarakat. Raih penghargaan, sertifikat resmi, dan kesempatan mewakili fakultas di ajang Pilmapres tingkat universitas.
                    </p>
                    <div class="mt-4">
                        <a href="/papan-peringkat" class="btn btn-warning rounded-pill py-2 px-5 btn-brand-orange">
                            Lihat Peringkat Sementara
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-center mt-4 mt-md-0">
                    <img src="{{ asset('images/mahasiswa.png') }}" alt="Mahasiswa Berprestasi" class="img-fluid img-full-bottom">
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold">
                        <span class="underline-accent">Pemilihan</span> Mahasiswa Berprestasi (PILMAPRES)
                    </h2>
                    <p class="mt-4 fs-6 section-text">
                        Pilmapres atau Pemilihan Mahasiswa Berprestasi merupakan ajang kompetisi tahunan bergengsi yang diselenggarakan oleh Balai Pengembangan Talenta Indonesia (BPTI) di bawah naungan Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi Republik Indonesia.
                    </p>
                    <div class="border-start border-4 border-warning ps-3 mt-4">
                        <p class="mb-0 fst-italic">
                            <b>Tujuan pelaksanaan Pilmapres di tingkat fakultas adalah untuk menjaring mahasiswa terbaik</b> yang akan menjadi representasi universitas di tingkat wilayah hingga nasional, serta untuk memberikan apresiasi kepada mahasiswa yang berhasil mencapai prestasi unggul.
                        </p>
                    </div>
                    <p class="mt-4 section-text">
                        Informasi lebih lanjut kunjungi laman resmi PILMAPRES: <a href="https://pusatprestasinasional.kemdikbud.go.id/" target="_blank" class="fw-bold">Pusat Prestasi Nasional</a>
                    </p>
                </div>
                <div class="col-md-4 text-center d-none d-md-block">
                    <img src="{{ asset('images/logo-pilmapres.png') }}" class="img-fluid" alt="Trofi Pilmapres Nasional">
                </div>
            </div>
        </div>
    </section>

    <section id="panduan" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold underline-accent">Panduan dan Syarat Pendaftaran</h2>
            </div>
            <div class="row g-4 align-items-start">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold">Persyaratan Umum Peserta</h4>
                            <ul class="list-unstyled mt-3">
                                <li class="d-flex mb-2"><i class="bi bi-check-circle-fill text-orange me-3 mt-1"></i><span>Mahasiswa aktif program studi di FST UNJA maksimal semester VIII.</span></li>
                                <li class="d-flex mb-2"><i class="bi bi-check-circle-fill text-orange me-3 mt-1"></i><span>Usia maksimal 23 tahun (dibuktikan dengan KTP).</span></li>
                                <li class="d-flex mb-2"><i class="bi bi-check-circle-fill text-orange me-3 mt-1"></i><span>Belum pernah menjadi finalis Pilmapres tingkat nasional.</span></li>
                                <li class="d-flex mb-2"><i class="bi bi-check-circle-fill text-orange me-3 mt-1"></i><span>Berpakaian layak dan memakai jas almamater UNJA saat seleksi.</span></li>
                            </ul>
                            <hr class="my-4">
                            <h4 class="fw-bold">Dokumen yang Dipersiapkan</h4>
                            <ul class="list-unstyled mt-3">
                                <li class="d-flex mb-2"><i class="bi bi-file-earmark-text-fill text-orange me-3 mt-1"></i><span>KRS (Semester terakhir) & KTP.</span></li>
                                <li class="d-flex mb-2"><i class="bi bi-file-earmark-text-fill text-orange me-3 mt-1"></i><span>Naskah Gagasan Kreatif (GK) dalam format PDF.</span></li>
                                <li class="d-flex mb-2"><i class="bi bi-file-earmark-text-fill text-orange me-3 mt-1"></i><span>Slide presentasi GK dalam bahasa Inggris.</span></li>
                                <li class="d-flex mb-2"><i class="bi bi-file-earmark-text-fill text-orange me-3 mt-1"></i><span>Bukti Capaian Unggulan (CU) terbaik, maksimal 10 CU.</span></li>
                            </ul>
                            <div class="mt-4">
                                <a href="{{ route('panduan.preview') }}" target="_blank" class="btn btn-warning rounded-pill px-4" style="background-color: #FD871F; border-color: #FD871F; color: #00135E;">
                                    <i class="bi bi-download me-2"></i> Unduh Panduan Lengkap (PDF)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3 class="fw-bold mb-3">Kriteria Penilaian</h3>
                    <x-criteria-card title="Capaian Unggulan" percentage="45%">CU adalah capaian yang diraih oleh mahasiswa dari kegiatan intrakurikuler, kokurikuler, maupun ekstrakurikuler.</x-criteria-card>
                    <x-criteria-card title="Gagasan Kreatif" percentage="35%">GK adalah tulisan berisi gagasan kreatif peserta untuk penyelesaian permasalahan yang ada pada masyarakat.</x-criteria-card>
                    <x-criteria-card title="Bahasa Inggris" percentage="20%">Kemampuan dalam menggunakan bahasa Inggris secara lisan dan tulisan dalam konteks akademik maupun nonakademik.</x-criteria-card>
                </div>
            </div>
        </div>
    </section>

    <section id="timeline" class="py-5">
        <div class="container">
            <div class="p-5 timeline-container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <h2 class="fw-bold text-white text-center">Timeline Kegiatan</h2>
                        <p class="fs-5 text-center fw-semibold timeline-subtitle">PILMAPRES FST</p>
                    </div>
                </div>
                <div class="row justify-content-center mt-2">
                    <div class="col-lg-10">
                        <img src="{{ asset('images/timeline.png') }}" alt="Gambar timeline kegiatan Pilmapres FST" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="rekam-jejak" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center mb-2">
                <div class="text-center">
                    <h2 class="fw-bold underline-accent">Rekam Jejak PILMAPRES</h2>
                    <p class="text-muted">Fakultas Sains dan Teknologi</p>
                    <div class="carousel-tahun-box">
                        <h6 id="carousel-tahun-title" class="carousel-tahun-title">Pemenang Tahun...</h6>
                    </div>
                </div>
            </div>

            @if ($rekamJejakGrouped->isNotEmpty())
                <div id="pemenangCarousel" class="carousel slide carousel-dark" data-bs-ride="carousel" data-bs-touch="true">
                    <div class="carousel-inner">
                        @foreach ($rekamJejakGrouped as $tahun => $pemenangGroup)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}" data-tahun="{{ $tahun }}">
                                <div class="row g-4 justify-content-center">
                                    @foreach ($pemenangGroup as $pemenang)
                                        <x-winner-card :pemenang="$pemenang" />
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($rekamJejakGrouped->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#pemenangCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#pemenangCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>

                        <div class="carousel-indicators">
                            @foreach ($rekamJejakGrouped as $tahun => $pemenangGroup)
                                <button type="button" data-bs-target="#pemenangCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <p>Belum ada data rekam jejak yang bisa ditampilkan.</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/landing-script.js') }}"></script>
@endpush