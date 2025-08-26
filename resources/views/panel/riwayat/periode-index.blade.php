@extends('layouts.panel')

@section('title', 'Riwayat Periode Pilmapres')

@section('content')
    <div class="page-heading">
        <h3>Riwayat Periode Pilmapres</h3>
        <p class="text-subtitle text-muted">Arsip penyelenggaraan Pilmapres FST dari tahun ke tahun.</p>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="row">
                @forelse ($riwayat as $item)
                    {{-- Memanggil komponen baru --}}
                    <x-riwayat-card :item="$item" />
                @empty
                    <div class="col-12">
                        <div class="alert alert-light-info">
                            Belum ada riwayat periode yang tersimpan.
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection