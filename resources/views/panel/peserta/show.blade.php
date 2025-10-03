@extends('layouts.panel')

@section('title', 'Manajemen Berkas Peserta')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Berkas Peserta</h3>
                    <p class="text-subtitle text-muted">Upload dan kelola berkas untuk: <strong>{{ $peserta->nama_lengkap }}</strong>.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('panel.peserta.index') }}">Daftar Peserta</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Peserta</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            @include('partials.panel._notification')

            @if ($periodeAktif && $periodeAktif->status != 'pendaftaran')
                <div class="alert alert-info">
                    <h4 class="alert-heading">Tahap Pendaftaran Telah Ditutup</h4>
                    <p>Periode saat ini berada pada tahap penilaian. Berkas hanya dapat dilihat. Tindakan penolakan administratif tetap dapat dilakukan.</p>
                </div>
            @endif

            @if ($berkas->get('CU') === null || $berkas->get('CU')->isEmpty())
                <div class="alert alert-light-warning">
                    <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Perhatian</h4>
                    <p>Belum ada Bukti Capaian Unggulan (CU) yang diunggah untuk peserta ini. Silakan unggah berkas CU yang relevan untuk kelengkapan data dan proses penilaian.</p>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center py-3">
                                <div class="avatar shadow-sm mb-3" style="width: 120px; height: 120px;">
                                    <img src="{{ $peserta->foto_path ? asset('storage/' . $peserta->foto_path) : asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                                <h3 class="mb-1">{{ $peserta->nama_lengkap }}</h3>
                                <p class="text-muted mb-3">
                                    {{ $peserta->nim }} &bull; {{ $peserta->prodi }} <br>
                                    Angkatan {{ $peserta->angkatan }} &bull; IPK {{ number_format($peserta->ipk, 2) }}
                                </p>
                                <x-status-badge :status="$peserta->status_verifikasi" :berkas-lengkap="$peserta->berkas_lengkap" />
                            </div>
                            <hr>
                            <h6>Informasi Kontak:</h6>
                            <p class="mb-1"><strong>Email:</strong> {{ $peserta->email }}</p>
                            <p><strong>No. HP:</strong> {{ $peserta->no_hp }}</p>
                            <hr>
                            <h6>Aksi Pendaftaran:</h6>
                            <div class="d-grid gap-2">
                                @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
                                    @if ($peserta->status_verifikasi == 'menunggu' || $peserta->status_verifikasi == 'ditolak')
                                        <form action="{{ route('panel.peserta.validasi', $peserta->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success w-100" @if (!$peserta->berkas_lengkap) disabled @endif>
                                                <i class="bi bi-check-circle-fill"></i> Validasi Peserta
                                            </button>
                                        </form>
                                        @if (!$peserta->berkas_lengkap)
                                            <div class="form-text text-center text-danger mt-1">
                                                <i class="bi bi-info-circle-fill"></i> Tombol validasi aktif setelah semua berkas wajib diunggah.
                                            </div>
                                        @endif
                                    @endif

                                    @if ($peserta->status_verifikasi == 'diverifikasi')
                                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#batalVerifikasiModal">
                                            <i class="bi bi-x-circle-fill"></i> Batalkan Verifikasi
                                        </button>
                                    @endif

                                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editPesertaModal" data-id="{{ $peserta->id }}" data-nama_lengkap="{{ $peserta->nama_lengkap }}" data-nim="{{ $peserta->nim }}" data-prodi="{{ $peserta->prodi }}" data-angkatan="{{ $peserta->angkatan }}" data-email="{{ $peserta->email }}" data-no_hp="{{ $peserta->no_hp }}" data-ipk="{{ $peserta->ipk }}">
                                        <i class="bi bi-pencil-fill"></i> Edit Data Peserta
                                    </button>
                                @endif

                                @if ($peserta->status_verifikasi != 'ditolak')
                                    <button type="button" class="btn btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#tolakPendaftaranModal">
                                        <i class="bi bi-trash-fill"></i> Diskualifikasi Peserta
                                    </button>
                                @endif
                                
                                @if (!$periodeAktif || $periodeAktif->status == 'selesai')
                                    <p class="text-center text-muted">Tidak ada aksi pendaftaran yang tersedia pada tahap ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Manajemen Berkas Peserta</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordionBerkas">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingBerkasWajib"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBerkasWajib">Berkas Wajib</button></h2>
                                    <div id="collapseBerkasWajib" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <table class="table table-hover mb-0">
                                                <tbody>
                                                    @foreach ($berkasWajibList as $key => $nama)
                                                        @php $file = $berkas->get($key)?->first(); @endphp
                                                        <tr>
                                                            <td>{{ $nama }}</td>
                                                            <td>
                                                                @if ($file)
                                                                    <span class="badge bg-success">Tersedia</span>
                                                                @else
                                                                    <span class="badge bg-danger">Belum Ada</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($file)
                                                                    <div class="d-inline-flex gap-1">
                                                                        <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                                                        @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
                                                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBerkasModal" data-nama-berkas="{{ $nama }}" data-update-url="{{ route('panel.berkas.update', $file->id) }}">Ganti</button>
                                                                            <form action="{{ route('panel.berkas.destroy', $file->id) }}" method="POST" class="m-0 form-delete" data-confirm-text="Hapus berkas {{ $nama }}?">
                                                                                @csrf @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
                                                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBerkasModal" data-jenis-berkas="{{ $key }}" data-nama-berkas="{{ $nama }}" data-store-url="{{ route('panel.berkas.store', $peserta->id) }}">
                                                                            <i class="bi bi-upload"></i> Upload
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCu">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCu">
                                            <i class="bi bi-award-fill me-2"></i> Bukti Capaian Unggulan (Maks. 10)
                                            <span class="badge bg-info ms-auto">{{ $berkas->get('CU')?->count() ?? 0 }}/10 Terupload</span>
                                        </button>
                                    </h2>
                                    <div id="collapseCu" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Deskripsi Prestasi</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($berkas->get('CU') ?? [] as $cu)
                                                        <tr>
                                                            <td>{{ $cu->nama_berkas }}</td>
                                                            <td><span class="badge bg-success">Tersedia</span></td>
                                                            <td>
                                                                <div class="d-inline-flex gap-1">
                                                                    <a href="{{ asset('storage/' . $cu->path_file) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                                                    @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
                                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCuModal" data-update-url="{{ route('panel.berkas.update', $cu->id) }}" data-nama-berkas="{{ $cu->nama_berkas }}">Edit</button>
                                                                        <form action="{{ route('panel.berkas.destroy', $cu->id) }}" method="POST" class="m-0 form-delete" data-confirm-text="Hapus capaian unggulan '{{ $cu->nama_berkas }}'?">
                                                                            @csrf @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">Belum ada Capaian Unggulan yang diunggah.</td>
                                                        </tr>
                                                    @endforelse

                                                    @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
                                                        @for ($i = $berkas->get('CU')?->count() ?? 0; $i < 10; $i++)
                                                            <tr>
                                                                <td class="text-muted">Capaian Unggulan ({{ $i + 1 }}/10)</td>
                                                                <td><span class="badge bg-danger">Belum Ada</span></td>
                                                                <td>
                                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBerkasModal" data-jenis-berkas="CU" data-nama-berkas="Capaian Unggulan ({{ $i + 1 }}/10)" data-store-url="{{ route('panel.berkas.store', $peserta->id) }}">
                                                                        <i class="bi bi-upload"></i> Upload
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
        @include('panel.peserta._edit-peserta-modal')
        @include('panel.peserta._upload-berkas-modal')
        @include('panel.peserta._edit-cu-modal')
    @endif
    
    @if ($peserta->status_verifikasi == 'diverifikasi')
        @include('panel.peserta._batal-verifikasi-modal')
    @endif
    @if ($peserta->status_verifikasi != 'ditolak')
        @include('panel.peserta._diskualifikasi-peserta-modal')
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/manajemen-peserta.js') }}"></script>
@endpush
