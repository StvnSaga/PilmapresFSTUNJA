@extends('layouts.panel')

@section('title', 'Penilaian Bahasa Inggris')

@section('content')
<div class="page-heading">
    <h3>Penilaian Bahasa Inggris (BI)</h3>
    <p class="text-subtitle text-muted">Tinjau slide presentasi dan berikan skor sesuai rubrik yang berlaku.</p>
</div>

<div class="page-content">
    <section class="section">
        @include('partials.panel._notification')
        
        @if ($penilaianLocked)
            <div class="alert alert-warning">
                <h4 class="alert-heading">Penilaian Belum Dibuka</h4>
                <p>Admin belum memulai tahap penilaian untuk periode ini.</p>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('juri.penilaian.bi') }}">
                        <div class="form-group row align-items-center">
                            <label for="pilih-peserta" class="col-md-3 col-form-label">Pilih Peserta untuk Dinilai:</label>
                            <div class="col-md-9">
                                <select class="form-select" id="pilih-peserta" name="peserta_id" onchange="this.form.submit()">
                                    <option value="">-- Pilih salah satu peserta --</option>
                                    @foreach ($pesertas as $peserta)
                                        <option value="{{ $peserta->id }}" @if(optional($selectedPeserta)->id == $peserta->id) selected @endif>
                                            {{ $peserta->nama_lengkap }} ({{ $peserta->nim }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if ($selectedPeserta)
            @php
                $isFinal = $penilaian->status_penilaian_bi == 'final';
            @endphp
            <div class="row">
                <div class="col-lg-8">
                    {{-- Panel preview dokumen yang akan tetap terlihat saat scroll --}}
                    <div class="card sticky-top">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">"{{ optional($berkasSlideGk)->nama_berkas ?? 'Judul Berkas Tidak Tersedia' }}"</h5>
                                @if($berkasSlideGk)
                                    <a href="{{ asset('storage/' . $berkasSlideGk->path_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrows-fullscreen"></i> Perbesar
                                    </a>
                                @endif
                            </div>
                            <hr class="my-3">
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                        <td class="p-0 info-table-label">Nama</td>
                                        <td class="p-0"><strong>: {{ $selectedPeserta->nama_lengkap }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="p-0 info-table-label">Program Studi</td>
                                        <td class="p-0"><strong>: {{ $selectedPeserta->prodi }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr class="my-4">
                            
                            @if ($berkasSlideGk)
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ asset('storage/' . $berkasSlideGk->path_file) }}" title="Preview Slide PDF" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="bg-light text-center d-flex align-items-center justify-content-center preview-placeholder">
                                    <p class="text-muted m-0">Berkas slide presentasi peserta belum diunggah.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <form id="form-penilaian-bi" method="POST" action="{{ route('juri.penilaian.bi.store') }}">
                        @csrf
                        <input type="hidden" name="penilaian_id" value="{{ $penilaian->id }}">

                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Formulir Penilaian</h4></div>
                            <div class="card-body">
                                @if($isFinal)
                                    <div class="alert alert-success text-center"><i class="bi bi-check-circle-fill"></i> Penilaian Telah Dikunci</div>
                                @endif
                                @foreach ($scoringCriteria as $item)
                                    <x-scoring-input 
                                        :item="$item" 
                                        :value="old('skor.' . $item['field'], $penilaian->skor_bi_detail[$item['field']] ?? '')"
                                        :catatan="old('catatan_bi.' . $item['field'], $penilaian->catatan_bi_detail[$item['field']] ?? '')"
                                        :disabled="$isFinal"
                                    />
                                @endforeach
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Ringkasan & Finalisasi</h4></div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between bg-light">
                                        <h5 class="mb-0">Total Skor BI:</h5>
                                        <h5 class="mb-0 text-primary" id="total-skor-bi">0 / 100</h5>
                                    </li>
                                </ul>
                                <div class="form-group mt-3">
                                    <label for="catatan_bi" class="form-label">Catatan Juri (Opsional)</label>
                                    <textarea class="form-control" id="catatan_bi" name="catatan_juri_bi" rows="4" placeholder="Berikan feedback atau justifikasi..." {{ $isFinal ? 'disabled' : '' }}>{{ old('catatan_juri_bi', $penilaian->catatan_juri_bi ?? '') }}</textarea>
                                </div>

                                @if(!$isFinal)
                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary flex-grow-1">Simpan Draf</button>
                                    <button type="submit" name="finalisasi_bi" value="true" class="btn btn-success btn-finalize" data-confirm-text="Anda yakin ingin mengunci penilaian BI ini? Anda tidak akan bisa mengubahnya lagi.">
                                        Simpan & Kunci
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        @endif
    </section>
</div>
@endsection

@push('scripts')
    @if (!isset($penilaianLocked) || !$penilaianLocked)
        <script src="{{ asset('assets/js/penilaian-bi.js') }}"></script>
    @endif
@endpush
