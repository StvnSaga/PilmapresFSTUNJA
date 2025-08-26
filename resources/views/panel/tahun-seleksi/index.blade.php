@extends('layouts.panel')

@section('title', 'Manajemen Tahun Seleksi')

@section('content')
<div class="page-heading">
    <h3>Manajemen Tahun Seleksi</h3>
    <p class="text-subtitle text-muted">Pantau dan kelola periode pelaksanaan Pilmapres secara terpusat.</p>
</div>

<div class="page-content">
    @include('partials.panel._notification')
    <section class="row">
        <div class="col-12 col-lg-4 col-md-6">
            <x-statistik-card color="blue" icon="iconly-boldCalendar" title="Tahun Seleksi Aktif"
                value="{{ $periodeAktif ? $periodeAktif->tahun : 'Belum Ditetapkan' }}" />
        </div>
        <div class="col-12 col-lg-4 col-md-6">
            <x-statistik-card color="green" icon="iconly-boldUser" title="Peserta di Tahun Aktif"
                value="{{ $jumlahPesertaDiTahunAktif ?? 0 }} Peserta" />
        </div>
        <div class="col-12 col-lg-4 col-md-6">
            <x-statistik-card color="red" icon="iconly-boldChart" title="Tahapan Seleksi"
                value="{{ $periodeAktif ? ucfirst($periodeAktif->status) : 'N/A' }}" />
        </div>
    </section>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Pengaturan Periode</h4>
                
                @if ($bisaTambahPeriodeBaru)
                    {{-- Tombol aktif jika tidak ada periode berjalan --}}
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTahunModal">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Periode Baru
                    </button>
                @else
                    {{-- Tombol nonaktif dengan tooltip penjelasan --}}
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Selesaikan periode yang sedang berjalan untuk menambah periode baru.">
                        <button class="btn btn-primary" type="button" disabled>
                            <i class="bi bi-plus-circle-fill"></i> Tambah Periode Baru
                        </button>
                    </span>
                @endif
            </div>
            
            {{-- ================================================================== --}}
            {{-- ==== PERUBAHAN YANG DIBUAT ADA DI BARIS DI BAWAH INI ==== --}}
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
            {{-- ================================================================== --}}

                <div class="list-group">
                    @forelse ($periodes as $periode)
                        {{-- Item aktif diberi latar, shadow, dan border. Item tidak aktif hanya border. --}}
                        <div class="list-group-item p-3 
                            @if($periode->is_active) list-group-item-primary shadow-sm border-primary @else border-start-4 border-light @endif">
                            
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        {{-- Judul dibuat tebal dan berwarna utama jika aktif, tanpa ikon --}}
                                        <h5 class="mb-0 me-2 @if($periode->is_active) fw-bold text-primary @endif">
                                            Periode {{ $periode->tahun }}
                                        </h5>
                                        
                                        {{-- Badge 'Aktif' dibuat solid agar lebih kontras --}}
                                        @if($periode->is_active)
                                            <span class="badge bg-primary">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </div>  
                                    
                                    <p class="mb-1 text-muted">
                                        Tahapan: <span class="badge rounded-pill {{ 
                                            $periode->status == 'pendaftaran' ? 'bg-info' : 
                                            ($periode->status == 'penilaian' ? 'bg-warning' : 'bg-success') 
                                        }}">{{ ucfirst($periode->status) }}</span>
                                    </p>
                                </div>
                                
                                <div class="btn-group" role="group">
                                    {{-- Logika tombol-tombol aksi --}}
                                    @if (!$periode->is_active && $periode->status != 'selesai')
                                        <form action="{{ route('admin.tahun-seleksi.setActive', $periode->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">Jadikan Aktif</button>
                                        </form>
                                    @elseif ($periode->is_active)
                                        @if ($periode->status == 'pendaftaran')
                                            <form action="{{ route('admin.tahun-seleksi.startPenilaian', $periode->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-info">Mulai Penilaian</button>
                                            </form>
                                        @elseif ($periode->status == 'penilaian')
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#akhirPeriodeModal{{ $periode->id }}">
                                                Akhiri Periode
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($periode->is_active && $periode->status == 'penilaian')
                            @include('panel.tahun-seleksi._akhir-periode-modal', ['periode' => $periode])
                        @endif
                    @empty
                        <div class="list-group-item text-center">Belum ada periode yang ditambahkan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>

@include('panel.tahun-seleksi._tambah-tahun-modal')
@endsection