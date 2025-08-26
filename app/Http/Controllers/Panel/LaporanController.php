<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use Illuminate\Http\Request;
use App\Exports\RekapNilaiExport; // <-- TAMBAHKAN INI
use Maatwebsite\Excel\Facades\Excel; // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\File; // Import File
use ZipArchive; // Import ZipArchive



class LaporanController extends Controller
{
    private function hitungDanUrutkanPeserta()
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif) {
            return collect();
        }

        $pesertasDiPeriodeIni = Peserta::where('tahun_seleksi_id', $periodeAktif->id)->get();

        foreach ($pesertasDiPeriodeIni as $peserta) {
            $totalCu = $peserta->berkas()->where('jenis_berkas', 'CU')->sum('skor');
            $avgGk = $peserta->penilaians()->avg('total_skor_gk');
            $avgBi = $peserta->penilaians()->avg('total_skor_bi');

            $bobotCu = 0.45;
            $bobotGk = 0.35;
            $bobotBi = 0.20;

            $skorAkhir = (($totalCu ?? 0) * $bobotCu) + (($avgGk ?? 0) * $bobotGk) + (($avgBi ?? 0) * $bobotBi);

            $peserta->update([
                'total_skor_cu' => $totalCu ?? 0,
                'total_skor_gk' => $avgGk ?? 0,
                'total_skor_bi' => $avgBi ?? 0,
                'skor_akhir' => $skorAkhir,
            ]);
        }

        return Peserta::where('tahun_seleksi_id', $periodeAktif->id)
            ->orderBy('skor_akhir', 'desc')
            ->get();
    }

    public function rekapNilai()
    {
        $pesertas = $this->hitungDanUrutkanPeserta();
        return view('panel.laporan.rekap-nilai', ['pesertas' => $pesertas]);
    }

    public function liveRanking()
    {
        // 1. Ambil semua peserta yang sudah diurutkan
        $pesertas = $this->hitungDanUrutkanPeserta();

        // 2. Ambil peserta peringkat pertama (jika ada)
        $topPerformer = $pesertas->first();

        // 3. Kirim kedua variabel ke view
        return view('panel.laporan.live-ranking', [
            'pesertas' => $pesertas,
            'topPerformer' => $topPerformer, // Kirim data top performer
        ]);
    }
    public function showDetail(Peserta $peserta, $peringkat)
    {
        $peserta->load('berkas', 'penilaians.juri');
        $berkasCu = $peserta->berkas->where('jenis_berkas', 'CU');
        $berkasGk = $peserta->berkas->where('jenis_berkas', 'NASKAH_GK')->first();
        $berkasSlideGk = $peserta->berkas->where('jenis_berkas', 'SLIDE_GK')->first();

        // ===================================================================
        // !! KODE BARU DITAMBAHKAN DI SINI !!
        // Definisi data kriteria sekarang ada di Controller, bukan di View.
        // ===================================================================
        $kriteriaGkMap = [
            '1_1' => 'Penggunaan Bahasa', '1_2' => 'Kesesuaian Pengutipan',
            '2_1' => 'Fakta/gejala', '2_2' => 'Identifikasi masalah', '2_3' => 'Rumusan masalah',
            '2_4' => 'Akibat pembiaran', '2_5' => 'Solusi SMART', '2_6' => 'Dampak lanjutan',
            '2_7' => 'Langkah tindakan', '2_8' => 'Kendala & antisipasi',
            '3_1' => 'Keunikan & Orisinalitas', '3_2' => 'Keterlaksanaan Gagasan',
        ];

        $kriteriaBiMap = [
            'content' => 'Content (Isi Materi)', 'accuracy' => 'Accuracy (Tata Bahasa & Kosakata)',
            'fluency' => 'Fluency (Kelancaran)', 'pronunciation' => 'Pronunciation (Pengucapan)',
            'performance' => 'Overall Performance (Sikap & Gestur)',
        ];
        // ===================================================================

        return view('panel.laporan.detail-rekap', [
            'peserta' => $peserta,
            'peringkat' => $peringkat,
            'berkasCu' => $berkasCu,
            'berkasGk' => $berkasGk,
            'berkasSlideGk' => $berkasSlideGk,
            // !! KODE BARU: Kirim data kriteria ke view !!
            'kriteriaGkMap' => $kriteriaGkMap,
            'kriteriaBiMap' => $kriteriaBiMap,
        ]);
    }

        public function exportRekapNilai()
    {
        // 1. Ambil data peserta yang sudah diperingkat
        $pesertas = $this->hitungDanUrutkanPeserta();
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        $fileName = 'Rekap-Nilai-Pilmapres-' . ($periodeAktif->tahun ?? 'data') . '.xlsx';

        // 2. Panggil class export dan unduh file
        return Excel::download(new RekapNilaiExport($pesertas), $fileName);
    }
    
        public function exportRekapNilaiZip()
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Tidak ada periode aktif untuk diekspor.']);
        }

        $pesertas = $this->hitungDanUrutkanPeserta();
        $namaFolder = 'Arsip-Pilmapres-' . $periodeAktif->tahun;
        $namaZip = $namaFolder . '.zip';
        $pathFolderTemp = storage_path('app/temp/' . $namaFolder);

        // Buat folder temporer jika belum ada
        if (!File::isDirectory($pathFolderTemp)) {
            File::makeDirectory($pathFolderTemp, 0755, true, true);
        }

        // 1. Simpan file Excel di dalam folder temporer
        $namaFileExcel = 'Rekap-Nilai-Pilmapres-' . $periodeAktif->tahun . '.xlsx';
        Excel::store(new RekapNilaiExport($pesertas), $namaFolder . '/' . $namaFileExcel, 'temp');

        // 2. Salin semua berkas peserta ke dalam folder temporer
        foreach ($pesertas as $peserta) {
            $folderPeserta = $pathFolderTemp . '/' . $peserta->nama_lengkap . ' - ' . $peserta->nim;
            if (!File::isDirectory($folderPeserta)) {
                File::makeDirectory($folderPeserta, 0755, true, true);
            }
            
            // Salin foto profil
            if ($peserta->foto_path && File::exists(storage_path('app/public/' . $peserta->foto_path))) {
                File::copy(storage_path('app/public/' . $peserta->foto_path), $folderPeserta . '/Foto Profil.' . pathinfo($peserta->foto_path, PATHINFO_EXTENSION));
            }
            
            // Salin semua berkas lainnya
            foreach ($peserta->berkas as $berkas) {
                 if ($berkas->path_file && File::exists(storage_path('app/public/' . $berkas->path_file))) {
                    File::copy(storage_path('app/public/' . $berkas->path_file), $folderPeserta . '/' . $berkas->nama_berkas . '.' . pathinfo($berkas->path_file, PATHINFO_EXTENSION));
                 }
            }
        }

        // 3. Buat file ZIP
        $zip = new ZipArchive;
        if ($zip->open(storage_path('app/temp/' . $namaZip), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = File::allFiles($pathFolderTemp);
            foreach ($files as $file) {
                $relativePath = substr($file->getPathname(), strlen($pathFolderTemp) + 1);
                $zip->addFile($file->getPathname(), $relativePath);
            }
            $zip->close();
        }

        // 4. Hapus folder temporer setelah ZIP dibuat
        File::deleteDirectory($pathFolderTemp);
        
        // 5. Kirim file ZIP untuk diunduh dan hapus setelahnya
        return response()->download(storage_path('app/temp/' . $namaZip))->deleteFileAfterSend(true);
    }
}
