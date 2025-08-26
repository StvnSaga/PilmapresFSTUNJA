<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\ManajemenUserController;
use App\Http\Controllers\Panel\TahunSeleksiController;
use App\Http\Controllers\Panel\PengaturanController;
use App\Http\Controllers\Panel\PesertaController;
use App\Http\Controllers\Panel\BerkasController;
use App\Http\Controllers\Panel\PenilaianCuController;
use App\Http\Controllers\Panel\PenilaianJuriController;
use App\Http\Controllers\Panel\LaporanController;
use App\Http\Controllers\Panel\RiwayatController;
use App\Http\Controllers\Panel\RekamJejakController;
use App\Http\Controllers\Panel\ProfilController;
use App\Http\Controllers\Panel\PanduanController;
use App\Http\Controllers\PublicController;

// RUTE PUBLIK
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/papan-peringkat', [PublicController::class, 'papanPeringkat'])->name('public.ranking');
Route::get('/Preview-Panduan-Pilmapres-FST', [FileController::class, 'showPilmapresPreview'])->name('panduan.preview');

// AUTH
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// RUTE PANEL YANG MEMBUTUHKAN LOGIN
Route::middleware(['auth'])->prefix('panel')->group(function () {
    
    // Rute Khusus Admin
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard-admin', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', ManajemenUserController::class)->names('admin.users');
        Route::resource('tahun-seleksi', TahunSeleksiController::class)->names('admin.tahun-seleksi');
        Route::put('/tahun-seleksi/{tahun_seleksi}/set-active', [TahunSeleksiController::class, 'setActive'])->name('admin.tahun-seleksi.setActive');
        Route::put('/tahun-seleksi/{tahunSeleksi}/start-penilaian', [TahunSeleksiController::class, 'startPenilaian'])->name('admin.tahun-seleksi.startPenilaian');
        Route::put('/tahun-seleksi/{tahunSeleksi}/end-period', [TahunSeleksiController::class, 'endPeriod'])->name('admin.tahun-seleksi.endPeriod');
        Route::resource('rekam-jejak', RekamJejakController::class)->except(['show', 'create', 'edit']);
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan.index');
        Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');
        Route::get('admin/riwayat', [RiwayatController::class, 'index'])->name('admin.riwayat.index');
        Route::get('admin/riwayat/{tahun}', [RiwayatController::class, 'periodeDetail'])->name('admin.riwayat.detail');
        Route::get('admin/laporan/rekap-nilai/{peserta}/{peringkat}', [LaporanController::class, 'showDetail'])->name('admin.laporan.detail-rekap');
        Route::get('admin/riwayat/{tahun}/export-excel', [RiwayatController::class, 'exportPeriodeDetail'])->name('admin.riwayat.export-excel');
        Route::get('admin/riwayat/{tahun}/export-zip', [RiwayatController::class, 'exportPeriodeDetailZip'])->name('admin.riwayat.export-zip');
    });

    // Rute Khusus Panitia
    Route::middleware('panitia')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('panitia.dashboard');
        
        // !! PERBAIKAN: Rute Peserta ditulis manual agar tidak ada konflik !!
        Route::get('/peserta', [PesertaController::class, 'index'])->name('panel.peserta.index');
        Route::post('/peserta', [PesertaController::class, 'store'])->name('panel.peserta.store');
        Route::get('/peserta/{peserta}', [PesertaController::class, 'show'])->name('panel.peserta.show');
        Route::put('/peserta/{peserta}', [PesertaController::class, 'update'])->name('panel.peserta.update');
        Route::delete('/peserta/{peserta}', [PesertaController::class, 'destroy'])->name('panel.peserta.destroy');

        Route::put('/peserta/{peserta}/validasi', [PesertaController::class, 'validasi'])->name('panel.peserta.validasi');
        Route::put('/peserta/{peserta}/unverify', [PesertaController::class, 'unverify'])->name('panel.peserta.unverify');
        Route::put('/peserta/{peserta}/reject', [PesertaController::class, 'reject'])->name('panel.peserta.reject');
        
        Route::post('/peserta/{peserta}/berkas', [BerkasController::class, 'store'])->name('panel.berkas.store');
        Route::delete('/berkas/{berkas}', [BerkasController::class, 'destroy'])->name('panel.berkas.destroy');
        Route::put('/berkas/{berkas}', [BerkasController::class, 'update'])->name('panel.berkas.update');
        
        Route::get('/penilaian-cu', [PenilaianCuController::class, 'index'])->name('panel.penilaian.capaian-unggulan');
        Route::post('/penilaian-cu', [PenilaianCuController::class, 'store'])->name('panel.penilaian.store-cu');
        
        Route::get('/rekap-nilai', [LaporanController::class, 'rekapNilai'])->name('panel.laporan.rekap-nilai');
        Route::get('/laporan/rekap-nilai/export-excel', [LaporanController::class, 'exportRekapNilai'])->name('panel.laporan.export-excel');
        Route::get('/laporan/rekap-nilai/export-zip', [LaporanController::class, 'exportRekapNilaiZip'])->name('panel.laporan.export-zip');
        Route::get('/laporan/rekap-nilai/{peserta}/{peringkat}', [LaporanController::class, 'showDetail'])->name('panel.laporan.detail-rekap');
        Route::get('/live-ranking', [LaporanController::class, 'liveRanking'])->name('panel.laporan.live-ranking');
        
        Route::get('/riwayat', [RiwayatController::class, 'index'])->name('panitia.riwayat.index');
        Route::get('/riwayat/{tahun}', [RiwayatController::class, 'periodeDetail'])->name('panitia.riwayat.detail');
        Route::get('/riwayat/{tahun}/export-excel', [RiwayatController::class, 'exportPeriodeDetail'])->name('panitia.riwayat.export-excel');
        Route::get('/riwayat/{tahun}/export-zip', [RiwayatController::class, 'exportPeriodeDetailZip'])->name('panitia.riwayat.export-zip');
    });

    // Rute Khusus Juri
    Route::middleware('juri')->group(function () {
        Route::get('/dashboard-juri', [DashboardController::class, 'index'])->name('juri.dashboard');
        Route::get('/penilaian/gagasan-kreatif', [PenilaianJuriController::class, 'gagasanKreatif'])->name('juri.penilaian.gk');
        Route::post('/penilaian/gagasan-kreatif', [PenilaianJuriController::class, 'storeGagasanKreatif'])->name('juri.penilaian.gk.store');
        Route::get('/penilaian/bahasa-inggris', [PenilaianJuriController::class, 'bahasaInggris'])->name('juri.penilaian.bi');
        Route::post('/penilaian/bahasa-inggris', [PenilaianJuriController::class, 'storeBahasaInggris'])->name('juri.penilaian.bi.store');
    });

    // Rute Umum untuk semua role yang login
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/panduan-penilaian', [PanduanController::class, 'show'])->name('panel.panduan');
});
