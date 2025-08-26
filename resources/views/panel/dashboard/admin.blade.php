@extends('layouts.panel')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="page-heading">
        <h3>Dashboard Admin</h3>
        <p class="text-subtitle text-muted">Selamat datang, {{ auth()->user()->name }}. Ringkasan dan status sistem.</p>
    </div>
    <div class="page-content">
        @include('partials.panel._notification')      
        <section class="row">
            <div class="col-12 col-lg-3 col-md-6">
                <x-statistik-card color="purple" icon="iconly-boldProfile" title="Jumlah Panitia"
                    value="{{ $statistik['jumlahPanitia'] ?? 0 }} Pengguna" />
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <x-statistik-card color="green" icon="iconly-boldUser" title="Jumlah Juri"
                    value="{{ $statistik['jumlahJuri'] ?? 0 }} Pengguna" />
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <x-statistik-card color="blue" icon="iconly-boldCalendar" title="Tahun Seleksi Aktif"
                    value="{{ optional($statistik['periodeAktif'])->tahun ?? 'Belum Ada' }}" />
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <x-statistik-card color="red" icon="iconly-boldActivity" title="Tahap Aktif"
                    value="{{ ucfirst($statistik['tahapanAktif']) }}" />
            </div>
        </section>
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Log Aktivitas Terbaru</h4>
                    </div>
                    <div class="card-body">
                        {{-- !! PERUBAHAN UTAMA ADA DI SINI !! --}}
                        {{-- 1. Kita tambahkan div pembungkus dengan style --}}
                        <div style="max-height: 450px; overflow-y: auto;">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        @forelse ($statistik['logs'] as $log)
                                            <tr>
                                                <td>
                                                    <i class="bi bi-person-circle me-2"></i>
                                                    <strong>{{ $log->user->name ?? 'Sistem' }}</strong> {{ $log->description }}
                                                </td>
                                                {{-- 2. Kita tambahkan style agar waktu tidak terpotong --}}
                                                <td class="text-muted text-end" style="white-space: nowrap;">{{ $log->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center text-muted py-3">
                                                    Belum ada aktivitas terbaru untuk ditampilkan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                         {{-- !! AKHIR DARI PERUBAHAN !! --}}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
