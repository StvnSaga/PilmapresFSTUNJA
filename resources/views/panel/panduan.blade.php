@extends('layouts.panel')

@section('title', 'Panduan & Rubrik Penilaian')

@section('content')
    <div class="page-heading">
        <h3>Panduan & Rubrik Penilaian</h3>
        <p class="text-subtitle text-muted">Ringkasan kriteria penilaian Pilmapres FST UNJA dalam format interaktif.</p>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="row mb-4">
                {{-- PERUBAHAN: Menggunakan komponen, bukan kartu manual --}}
                <div class="col-lg-4 col-md-6 col-12">
                    {{-- Kartu ini tidak bisa diklik, jadi kita tidak tambahkan id/class card-link --}}
                    <div class="card-link" id="card-cu">
                        <x-statistik-card color="purple" icon="iconly-boldStar" title="Capaian Unggulan" value="45%" />
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    {{-- Tambahkan atribut class dan id ke komponen --}}
                    <div class="card-link" id="card-gk">
                        <x-statistik-card color="green" icon="iconly-boldWork" title="Gagasan Kreatif" value="35%" />
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card-link" id="card-bi">
                        <x-statistik-card color="blue" icon="iconly-boldVoice" title="Bahasa Inggris" value="20%" />
                    </div>
                </div>
            </div>

            <div class="accordion" id="panduanAccordion">
                {{-- Bagian Accordion dan Tabel tidak diubah, karena ini adalah data statis --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            <i class="bi bi-card-checklist text-primary me-2"></i> Detail Rubrik Capaian Unggulan (CU)
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#panduanAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-cu">
                                    <thead class="table-purple">
                                        <tr>
                                            <th rowspan="2">Bidang</th>
                                            <th rowspan="2">Wujud Capaian Unggulan</th>
                                            <th colspan="2">Kategori A / Internasional</th>
                                            <th colspan="2">Kategori B / Regional</th>
                                            <th colspan="2">Kategori C / Nasional</th>
                                            <th colspan="2">Kategori D / Provinsi</th>
                                            <th colspan="2">Kategori E / Kab/Kota/PT</th>
                                        </tr>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Skor</th>
                                            <th>Kode</th>
                                            <th>Skor</th>
                                            <th>Kode</th>
                                            <th>Skor</th>
                                            <th>Kode</th>
                                            <th>Skor</th>
                                            <th>Kode</th>
                                            <th>Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Data dari file PDF dimasukkan di sini --}}
                                        <tr>
                                            <td rowspan="8" class="text-start"><strong>Kompetisi</strong></td>
                                            <td class="text-start">Juara-1 Perorangan</td>
                                            <td>1A1</td><td>40-50</td>
                                            <td>1B1</td><td>30-40</td>
                                            <td>1C1</td><td>20-30</td>
                                            <td>1D1</td><td>20</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Juara-2 Perorangan</td>
                                            <td>1A2</td><td>35-45</td>
                                            <td>1B2</td><td>25-35</td>
                                            <td>1C2</td><td>15-25</td>
                                            <td>1D2</td><td>15</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Juara-3 Perorangan</td>
                                            <td>1A3</td><td>30-40</td>
                                            <td>1B3</td><td>20-30</td>
                                            <td>1C3</td><td>10-20</td>
                                            <td>1D3</td><td>10</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                         <tr>
                                            <td class="text-start">Kategori Juara Perorangan</td>
                                            <td>1A4</td><td>24-32</td>
                                            <td>1B4</td><td>16-24</td>
                                            <td>1C4</td><td>8-16</td>
                                            <td>1D4</td><td>8</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Juara-1 Beregu</td>
                                            <td>1A5</td><td>30-40</td>
                                            <td>1B5</td><td>20-30</td>
                                            <td>1C5</td><td>10-20</td>
                                            <td>1D5</td><td>10</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Juara-2 Beregu</td>
                                            <td>1A6</td><td>25-35</td>
                                            <td>1B6</td><td>15-25</td>
                                            <td>1C6</td><td>7-15</td>
                                            <td>1D6</td><td>7</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Juara-3 Beregu</td>
                                            <td>1A7</td><td>20-30</td>
                                            <td>1B7</td><td>10-20</td>
                                            <td>1C7</td><td>6-10</td>
                                            <td>1D7</td><td>6</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Juara Kategori Beregu</td>
                                            <td>1A8</td><td>16-24</td>
                                            <td>1B8</td><td>10-16</td>
                                            <td>1C8</td><td>5-10</td>
                                            <td>1D8</td><td>5</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        {{-- Lanjutan data... --}}
                                        <tr>
                                            <td rowspan="8" class="text-start"><strong>Karir Organisasi</strong></td>
                                            <td class="text-start">Ketua</td>
                                            <td>4A1</td><td>50</td>
                                            <td>4B1</td><td>40</td>
                                            <td>4C1</td><td>30</td>
                                            <td>4D1</td><td>20</td>
                                            <td>4E1</td><td>10</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Wakil Ketua</td>
                                            <td>4A2</td><td>45</td>
                                            <td>4B2</td><td>35</td>
                                            <td>4C2</td><td>25</td>
                                            <td>4D2</td><td>15</td>
                                            <td>4E2</td><td>8</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Sekretaris</td>
                                            <td>4A3</td><td>40</td>
                                            <td>4B3</td><td>30</td>
                                            <td>4C3</td><td>20</td>
                                            <td>4D3</td><td>10</td>
                                            <td>4E3</td><td>6</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Bendahara</td>
                                            <td>4A4</td><td>40</td>
                                            <td>4B4</td><td>30</td>
                                            <td>4C4</td><td>20</td>
                                            <td>4D4</td><td>10</td>
                                            <td>4E4</td><td>6</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">Satu tingkat dibawah pengurus harian</td>
                                            <td>4A5</td><td>30</td>
                                            <td>4B5</td><td>20</td>
                                            <td>4C5</td><td>10</td>
                                            <td>4D5</td><td>5</td>
                                            <td>4E5</td><td>2</td>
                                        </tr>
                                        {{-- Sisanya bisa ditambahkan dengan pola yang sama --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="12" class="text-start text-muted">
                                                <small>
                                                    <em>* Disesuaikan dengan tingkat kesulitan/manfaat.</em><br>
                                                    <em>** Skor maksimal untuk kompetisi hanya diberikan bagi yang menjadi utusan sesuai kategori, dan satu kegiatan tidak boleh dinilai dua kali.</em>
                                                </small>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            <i class="bi bi-card-checklist text-success me-2"></i> Detail Rubrik Gagasan Kreatif (GK)
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-success">
                                        <tr>
                                            <th>No.</th>
                                            <th>Kriteria Penilaian</th>
                                            <th>Bobot</th>
                                            <th>Rentang Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-light"><th colspan="4">1. Penyajian Gagasan Kreatif (10%)</th></tr>
                                        <tr><td>1.1</td><td>Penggunaan bahasa Indonesia yang baik dan benar</td><td>5</td><td>5-10</td></tr>
                                        <tr><td>1.2</td><td>Kesesuaian pengutipan dan pengacuan</td><td>5</td><td>5-10</td></tr>
                                        <tr class="table-light"><th colspan="4">2. Substansi Gagasan Kreatif (70%)</th></tr>
                                        <tr><td>2.1</td><td>Fakta atau gejala dalam lingkungan</td><td>8</td><td>5-10</td></tr>
                                        <tr><td>2.2</td><td>Identifikasi masalah</td><td>8</td><td>5-10</td></tr>
                                        <tr><td>2.3</td><td>Rumusan masalah</td><td>10</td><td>5-10</td></tr>
                                        <tr><td>2.4</td><td>Uraian mengenai akibat pembiaran</td><td>8</td><td>5-10</td></tr>
                                        <tr><td>2.5</td><td>Uraian mengenai solusi yang berciri SMART</td><td>15</td><td>5-10</td></tr>
                                        <tr><td>2.6</td><td>Uraian mengenai dampak lanjutan (efek bola salju)</td><td>8</td><td>5-10</td></tr>
                                        <tr><td>2.7</td><td>Rincian uraian mengenai langkah-langkah tindakan</td><td>8</td><td>5-10</td></tr>
                                        <tr><td>2.8</td><td>Uraian mengenai kendala/hambatan</td><td>5</td><td>5-10</td></tr>
                                        <tr class="table-light"><th colspan="4">3. Kualitas Gagasan Kreatif (20%)</th></tr>
                                        <tr><td>3.1</td><td>Keunikan dan Orisinalitas Gagasan</td><td>10</td><td>5-10</td></tr>
                                        <tr><td>3.2</td><td>Keterlaksanaan Gagasan</td><td>10</td><td>5-10</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            <i class="bi bi-card-checklist text-info me-2"></i> Detail Rubrik Presentasi Bahasa Inggris (BI)
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-info">
                                        <tr>
                                            <th>Field</th>
                                            <th>Score</th>
                                            <th>Criteria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-light"><th colspan="3">CONTENT</th></tr>
                                        <tr><td>Excellent to Very Good</td><td>22-25</td><td>Demonstration of excellent mastery of the topic and comprehensive elaboration.</td></tr>
                                        <tr><td>Good to Average</td><td>18-21</td><td>-</td></tr>
                                        <tr><td>Fair to Poor</td><td>11-17</td><td>-</td></tr>
                                        <tr><td>Very Poor</td><td>5-10</td><td>-</td></tr>

                                        <tr class="table-light"><th colspan="3">ACCURACY</th></tr>
                                        <tr><td>Excellent to Very Good</td><td>22-25</td><td>Excellent mastery of grammar and vocabulary with all appropriate choice of expressions/register.</td></tr>
                                        <tr><td>Good to Average</td><td>18-21</td><td>-</td></tr>
                                        <tr><td>Fair to Poor</td><td>11-17</td><td>-</td></tr>
                                        <tr><td>Very Poor</td><td>5-10</td><td>-</td></tr>

                                        <tr class="table-light"><th colspan="3">FLUENCY</th></tr>
                                        <tr><td>Excellent to Very Good</td><td>16-20</td><td>Speech is very fluent; no unnatural pauses; all comprehensible.</td></tr>
                                        <tr><td>Good to Average</td><td>11-15</td><td>-</td></tr>
                                        <tr><td>Fair to Poor</td><td>8-10</td><td>-</td></tr>
                                        <tr><td>Very Poor</td><td>5-7</td><td>-</td></tr>

                                        <tr class="table-light"><th colspan="3">PRONUNCIATION</th></tr>
                                        <tr><td>Excellent to Very Good</td><td>16-20</td><td>Pronunciation is always intelligible and clear with excellent rhythm and stress pattern.</td></tr>
                                        <tr><td>Good to Average</td><td>11-15</td><td>-</td></tr>
                                        <tr><td>Fair to Poor</td><td>8-10</td><td>-</td></tr>
                                        <tr><td>Very Poor</td><td>5-7</td><td>-</td></tr>

                                        <tr class="table-light"><th colspan="3">OVERALL PERFORMANCE</th></tr>
                                        <tr><td>Excellent to Very Good</td><td>9-10</td><td>Posture, gestures, facial expressions, eye contact, and volume demonstrate excellent performance.</td></tr>
                                        <tr><td>Good to Average</td><td>7-8</td><td>-</td></tr>
                                        <tr><td>Fair to Poor</td><td>5-6</td><td>-</td></tr>
                                        <tr><td>Very Poor</td><td>3-4</td><td>-</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    {{-- Memanggil script dari file eksternal --}}
    <script src="{{ asset('assets/js/panduan.js') }}"></script>
@endpush