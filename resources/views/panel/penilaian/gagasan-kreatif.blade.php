@extends('layouts.panel')

@section('title', 'Penilaian Gagasan Kreatif')

@section('content')
    <div class="page-heading">
        <h3>Penilaian Gagasan Kreatif (GK)</h3>
        <p class="text-subtitle text-muted">Tinjau naskah dan berikan skor sesuai rubrik yang berlaku.</p>
    </div>

    <div class="page-content">
        <section class="section">
            @include('partials.panel._notification')
            
            @if (isset($penilaianLocked) && $penilaianLocked)
                <div class="alert alert-warning"><h4 class="alert-heading">Penilaian Belum Dibuka</h4><p>Admin belum memulai tahap penilaian untuk periode ini.</p></div>
            @else
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('juri.penilaian.gk') }}">
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
                    $isFinal = optional($penilaian)->status_penilaian_gk == 'final';
                @endphp
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card sticky-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">"{{ optional($berkasGk)->nama_berkas ?? 'Judul Tidak Tersedia' }}"</h5>
                                    @if($berkasGk)
                                    <a href="{{ asset('storage/' . $berkasGk->path_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrows-fullscreen"></i> Perbesar</a>
                                    @endif
                                </div>
                                <hr class="my-3">
                                <table class="table table-borderless table-sm mb-3">
                                    <tbody>
                                        <tr>
                                            <td class="p-0" style="width: 120px;">Nama</td>
                                            <td class="p-0" style="width: 1%;">:</td>
                                            <td class="p-0"><strong>{{ $selectedPeserta->nama_lengkap }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="p-0">Program Studi</td>
                                            <td class="p-0">:</td>
                                            <td class="p-0"><strong>{{ $selectedPeserta->prodi }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr class="my-4">
                                @if ($berkasGk)
                                <iframe src="{{ asset('storage/' . $berkasGk->path_file) }}" width="100%" class="preview-iframe"></iframe>
                                @else
                                <div class="bg-light text-center d-flex align-items-center justify-content-center" style="height: 80vh; border-radius: 6px;">
                                    <p class="text-muted">Berkas Naskah GK tidak ditemukan.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <form id="form-penilaian-gk" method="POST" action="{{ route('juri.penilaian.gk.store') }}">
                            @csrf
                            <input type="hidden" name="penilaian_id" value="{{ optional($penilaian)->id }}">
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Formulir Penilaian</h4></div>
                                <div class="card-body">
                                    @if($isFinal)
                                        <div class="alert alert-success text-center"><i class="bi bi-check-circle-fill"></i> Penilaian Telah Dikunci</div>
                                    @endif
                                    <div class="accordion" id="accordionPenilaian">
                                        @foreach ($scoringRubric as $sectionTitle => $criteria)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header"><button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->iteration }}">{{ $sectionTitle }}</button></h2>
                                            <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#accordionPenilaian">
                                                <div class="accordion-body">
                                                    @foreach ($criteria as $criterion)
                                                        <x-scoring-row-gk 
                                                            :criterion="$criterion" 
                                                            :value="old('skor.' . $criterion['key'], optional($penilaian)->skor_gk_detail[$criterion['key']] ?? '')"
                                                            :catatan="old('catatan_gk.' . $criterion['key'], optional($penilaian)->catatan_gk_detail[$criterion['key']] ?? '')"
                                                            :disabled="$isFinal"
                                                        />
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Ringkasan & Finalisasi</h4></div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between"><span>Penyajian:</span><strong id="total-penyajian">0.00 / 10.00</strong></li>
                                        <li class="list-group-item d-flex justify-content-between"><span>Substansi:</span><strong id="total-substansi">0.00 / 70.00</strong></li>
                                        <li class="list-group-item d-flex justify-content-between"><span>Kualitas:</span><strong id="total-kualitas">0.00 / 20.00</strong></li>
                                        <li class="list-group-item d-flex justify-content-between bg-light">
                                            <h5 class="mb-0">Total Skor GK:</h5><h5 class="mb-0 text-primary" id="total-gk">0.00 / 100.00</h5>
                                        </li>
                                    </ul>
                                    <div class="form-group mt-3">
                                        <label for="catatan_gk" class="form-label">Catatan Juri (Opsional)</label>
                                        <textarea class="form-control" id="catatan_gk" name="catatan_juri_gk" rows="4" placeholder="Berikan feedback..." {{ $isFinal ? 'disabled' : '' }}>{{ old('catatan_juri_gk', optional($penilaian)->catatan_juri_gk ?? '') }}</textarea>
                                    </div>
                                    
                                    @if(!$isFinal)
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary flex-grow-1">Simpan Draf</button>
                                        {{-- !! PERUBAHAN DI SINI: Hapus onclick, tambahkan class & data attribute !! --}}
                                        <button type="submit" name="finalisasi_gk" value="true" 
                                                class="btn btn-success btn-finalize"
                                                data-confirm-text="Anda yakin ingin mengunci penilaian GK ini? Anda tidak akan bisa mengubahnya lagi.">
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
        <script src="{{ asset('assets/js/penilaian-gk.js') }}"></script>
    @endif
@endpush
