@extends('layouts.panel')

@section('title', 'Manajemen Rekam Jejak')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Rekam Jejak</h3>
                <p class="text-subtitle text-muted">Kelola pemenang yang akan ditampilkan di halaman depan.</p>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            @include('partials.panel._notification')

            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('rekam-jejak.index') }}" class="mb-4">
                        <div class="form-group">
                            <label for="tahun_seleksi_id">Pilih Periode yang Sudah Selesai</label>
                            <select name="tahun_seleksi_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Pilih Periode --</option>
                                @foreach ($periodesSelesai as $periode)
                                    <option value="{{ $periode->id }}" @if (optional($selectedPeriode)->id == $periode->id) selected @endif>
                                        {{ $periode->tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    @if ($selectedPeriode && $top3Pesertas->isNotEmpty())
                        <hr>
                        <form id="form-rekam-jejak" method="POST" action="{{ route('rekam-jejak.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tahun_seleksi_id" value="{{ $selectedPeriode->id }}">

                            <h4 class="mb-4 text-center">Atur Tampilan Pemenang Periode {{ $selectedPeriode->tahun }}</h4>

                            <div class="row align-items-stretch justify-content-center">
                                @foreach ($top3Pesertas as $peserta)
                                    @php
                                        $peringkat = $loop->iteration;
                                        $pemenangData = $rekamJejakData->get($peserta->id);
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="card bg-light border winner-form-card rank-{{ $peringkat }} h-100">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="text-center">Peringkat {{ $peringkat }}:
                                                    <strong>{{ $peserta->nama_lengkap }}</strong></h5>
                                                <p class="text-center text-muted mb-3">Skor Akhir:
                                                    {{ number_format($peserta->skor_akhir, 2) }}</p>

                                                <input type="hidden" name="pemenang[{{ $peserta->id }}][peringkat]"
                                                    value="{{ $peringkat }}">

                                                <div class="form-group">
                                                    <label>Unggah Foto Khusus (Opsional)</label>
                                                    <div class="winner-photo-preview mb-2">
                                                        {{-- !! LOGIKA BARU UNTUK MENAMPILKAN FOTO !! --}}
                                                        @php
                                                            $fotoTampil =
                                                                optional($pemenangData)->foto_path ??
                                                                $peserta->foto_path;
                                                        @endphp
                                                        <img src="{{ $fotoTampil ? asset('storage/' . $fotoTampil) : '' }}"
                                                            style="display: {{ $fotoTampil ? 'block' : 'none' }};">
                                                        @if (!$fotoTampil)
                                                            <i class="bi bi-image icon-placeholder"></i>
                                                        @endif
                                                    </div>
                                                    <input type="file" name="pemenang[{{ $peserta->id }}][foto]"
                                                        class="form-control form-control-sm" accept="image/*">
                                                    <small class="text-muted">Kosongkan jika ingin menggunakan foto profil
                                                        utama peserta.</small>
                                                </div>

                                                <div class="form-group mt-auto">
                                                    <label>Deskripsi Singkat</label>
                                                    <textarea name="pemenang[{{ $peserta->id }}][deskripsi]" class="form-control" rows="3"
                                                        placeholder="Contoh: Meraih Juara {{ $peringkat }} setelah presentasi yang memukau...">{{ optional($pemenangData)->deskripsi_singkat ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Simpan & Tampilkan di Rekam
                                    Jejak</button>
                            </div>
                        </form>
                    @elseif($selectedPeriode)
                        <div class="alert alert-light-warning">
                            Tidak ditemukan data peserta yang cukup untuk periode {{ $selectedPeriode->tahun }}.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/rekam-jejak.js') }}"></script>
@endpush
