@extends('layouts.panel')

@section('title', 'Manajemen Peserta')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Peserta</h3>
                <p class="text-subtitle text-muted">Kelola seluruh data peserta Pilmapres FST.</p>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            @include('partials.panel._notification')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Peserta</h5>
                    @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPesertaModal">
                            + Tambah Peserta
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if (!$periodeAktif)
                        <div class="alert alert-warning">
                            Tidak ada periode seleksi yang aktif. Silakan hubungi Admin.
                        </div>
                    @elseif ($periodeAktif->status != 'pendaftaran')
                        <div class="alert alert-info">
                            Tahap pendaftaran untuk periode ini telah ditutup.
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-peserta">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIM</th>
                                    <th>Program Studi</th>
                                    <th>Status Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesertas as $peserta)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $peserta->nama_lengkap }}</td>
                                        <td>{{ $peserta->nim }}</td>
                                        <td>{{ $peserta->prodi }}</td>
                                        <td>
                                            <div class="d-flex align-items-center h-100" style="position: relative; top: 2px;">
                                                @if ($peserta->status_verifikasi == 'menunggu')
                                                    @if ($peserta->berkas_lengkap)
                                                        <span class="badge bg-light-info">Siap Diverifikasi</span>
                                                    @else
                                                        <span class="badge bg-light-warning">Menunggu Berkas</span>
                                                    @endif
                                                @else
                                                    <x-status-badge :status="$peserta->status_verifikasi" />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('panel.peserta.show', $peserta->id) }}" class="btn btn-sm btn-info">Detail</a>
                                            @if ($periodeAktif && $periodeAktif->status == 'pendaftaran')
                                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal"
                                                    data-bs-target="#editPesertaModal" data-id="{{ $peserta->id }}"
                                                    data-nama_lengkap="{{ $peserta->nama_lengkap }}"
                                                    data-nim="{{ $peserta->nim }}" data-prodi="{{ $peserta->prodi }}"
                                                    data-angkatan="{{ $peserta->angkatan }}" data-email="{{ $peserta->email }}"
                                                    data-no_hp="{{ $peserta->no_hp }}" data-ipk="{{ $peserta->ipk }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('panel.peserta.destroy', $peserta->id) }}" method="POST" class="d-inline form-delete" data-confirm-text="Yakin ingin menghapus peserta '{{ $peserta->nama_lengkap }}'?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data peserta untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('panel.peserta._tambah-peserta-modal')
    @include('panel.peserta._edit-peserta-modal')
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/manajemen-peserta.js') }}"></script>
    <script>
        // Inisialisasi Simple Datatables untuk tabel peserta.
        document.addEventListener('DOMContentLoaded', function() {
            const dataTable = new simpleDatatables.DataTable(document.getElementById('table-peserta'));
        });
    </script>
@endpush
