@extends('layouts.panel')

@section('title', 'Penilaian Capaian Unggulan (CU)')

@section('content')
    <div class="page-heading">
        <h3>Penilaian Capaian Unggulan (CU)</h3>
        <p class="text-subtitle text-muted">Klasifikasi dan berikan skor pada setiap capaian unggulan peserta.</p>
    </div>

    <div class="page-content">
        @include('partials.panel._notification')
        
        @if (isset($penilaianLocked) && $penilaianLocked)
            <div class="alert alert-warning">
                <h4 class="alert-heading">Penilaian Belum Dibuka</h4>
                <p>{{ $lockMessage ?? "Admin belum memulai tahap penilaian untuk periode ini." }}</p>
            </div>
        @else
            <div class="card">
                <div class="card-header"><h4 class="card-title">Formulir Penilaian</h4></div>
                <div class="card-body">
                    <form method="GET" action="{{ route('panel.penilaian.capaian-unggulan') }}" class="mb-4">
                        <div class="form-group">
                            <label for="peserta_id" class="form-label">Pilih Peserta</label>
                            <select name="peserta_id" id="peserta_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Pilih salah satu peserta untuk dinilai --</option>
                                @foreach ($pesertas as $peserta)
                                    <option value="{{ $peserta->id }}" @if(optional($selectedPeserta)->id == $peserta->id) selected @endif>
                                        {{ $peserta->nama_lengkap }} ({{ $peserta->nim }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    @if ($selectedPeserta)
                        <hr>
                        <h5>Daftar Capaian Unggulan: <strong>{{ $selectedPeserta->nama_lengkap }}</strong></h5>
                        
                        @if($semuaCuFinal)
                            <div class="alert alert-success text-center mt-3">
                                <i class="bi bi-check-circle-fill"></i> Semua Penilaian CU Telah Dikunci
                            </div>
                        @endif

                        <form id="form-penilaian-cu" method="POST" action="{{ route('panel.penilaian.store-cu') }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Deskripsi Prestasi</th>
                                            <th>Bukti</th>
                                            <th>Bidang</th>
                                            <th>Wujud Capaian</th>
                                            <th>Tingkat</th>
                                            <th class="table-col-score">Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($berkasCu as $berkas)
                                            @php $isFinal = $berkas->status_penilaian == 'final'; @endphp
                                            <tr class="align-middle {{ $isFinal ? 'table-light' : '' }}">
                                                <td>
                                                    {{ $berkas->nama_berkas }}
                                                    @if($isFinal) <span class="badge bg-success ms-2">Terkunci</span> @endif
                                                </td>
                                                <td><a href="{{ asset('storage/' . $berkas->path_file) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a></td>
                                                <td>
                                                    <select name="klasifikasi[{{ $berkas->id }}][bidang]" class="form-select form-select-sm select-bidang" required {{ $isFinal ? 'disabled' : '' }}>
                                                        <option value="">Pilih Bidang</option>
                                                        @foreach ($klasifikasiCu as $bidang => $wujud)
                                                            <option value="{{ $bidang }}" @if(old('klasifikasi.'.$berkas->id.'.bidang', $berkas->cu_bidang) == $bidang) selected @endif>{{ $bidang }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="klasifikasi[{{ $berkas->id }}][wujud]" class="form-select form-select-sm select-wujud" required {{ $isFinal ? 'disabled' : '' }}>
                                                        <option value="">Pilih Wujud</option>
                                                        @foreach ($klasifikasiCu[old('klasifikasi.'.$berkas->id.'.bidang', $berkas->cu_bidang)] ?? [] as $wujud)
                                                            <option value="{{ $wujud }}" @if(old('klasifikasi.'.$berkas->id.'.wujud', $berkas->cu_wujud) == $wujud) selected @endif>{{ $wujud }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="klasifikasi[{{ $berkas->id }}][tingkat]" class="form-select form-select-sm select-tingkat" required {{ $isFinal ? 'disabled' : '' }}>
                                                        <option value="">Pilih Tingkat</option>
                                                        <option value="Internasional" @if(old('klasifikasi.'.$berkas->id.'.tingkat', $berkas->tingkat) == 'Internasional') selected @endif>Internasional</option>
                                                        <option value="Regional" @if(old('klasifikasi.'.$berkas->id.'.tingkat', $berkas->tingkat) == 'Regional') selected @endif>Regional</option>
                                                        <option value="Nasional" @if(old('klasifikasi.'.$berkas->id.'.tingkat', $berkas->tingkat) == 'Nasional') selected @endif>Nasional</option>
                                                        <option value="Provinsi" @if(old('klasifikasi.'.$berkas->id.'.tingkat', $berkas->tingkat) == 'Provinsi') selected @endif>Provinsi</option>
                                                        <option value="PT" @if(old('klasifikasi.'.$berkas->id.'.tingkat', $berkas->tingkat) == 'PT') selected @endif>Kab/Kota/PT</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.1" name="klasifikasi[{{ $berkas->id }}][skor]" class="form-control form-control-sm input-skor" value="{{ $berkas->skor }}" required {{ $isFinal ? 'disabled' : '' }}><small class="text-muted range-info"></small>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">Peserta ini belum mengunggah Capaian Unggulan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(!$semuaCuFinal && $berkasCu->isNotEmpty())
                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-primary">Simpan Draf</button>
                                    {{-- !! PERUBAHAN DI SINI: Hapus onclick, tambahkan class & data attribute !! --}}
                                    <button type="submit" name="finalisasi" value="true" 
                                            class="btn btn-success btn-finalize"
                                            data-confirm-text="Anda yakin ingin mengunci semua penilaian CU yang belum terkunci? Anda tidak akan bisa mengubahnya lagi.">
                                        Simpan & Kunci Semua
                                    </button>
                                </div>
                            @endif
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    {{-- !! KONDISI DI SINI YANG DIPERBAIKI !! --}}
    @if (isset($penilaianLocked) && !$penilaianLocked)
        <script>
            // Mengirim data dari PHP ke JavaScript eksternal
            const klasifikasiData = {!! json_encode($klasifikasiCu ?? []) !!};
            const skorTabelRange = {!! json_encode($skorTabelRange ?? []) !!};
        </script>
        <script src="{{ asset('assets/js/penilaian-cu.js') }}"></script>
    @endif
@endpush
