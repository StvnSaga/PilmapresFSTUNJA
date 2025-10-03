<?php

namespace App\Http\Controllers\Panel;

use App\Exports\RekapNilaiExport;
use App\Exports\RiwayatPeriodeExport;
use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class LaporanController extends Controller
{
    private function hitungDanUrutkanPeserta()
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif) {
            return collect();
        }

        $pesertasDiPeriodeIni = Peserta::where('tahun_seleksi_id', $periodeAktif->id)
            ->where('status_verifikasi', 'diverifikasi')
            ->get();

        foreach ($pesertasDiPeriodeIni as $peserta) {
            $totalCu = $peserta->berkas()->where('jenis_berkas', 'CU')->sum('skor');

            // Menghitung rata-rata skor GK hanya dari juri dengan spesialisasi 'GK'.
            $avgGk = $peserta->penilaians()
                ->whereHas('juri', function ($query) {
                    $query->where('jenis_juri', 'GK');
                })
                ->avg('total_skor_gk');

            // Menghitung rata-rata skor BI hanya dari juri dengan spesialisasi 'BI'.
            $avgBi = $peserta->penilaians()
                ->whereHas('juri', function ($query) {
                    $query->where('jenis_juri', 'BI');
                })
                ->avg('total_skor_bi');

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
            ->where('status_verifikasi', 'diverifikasi')
            ->orderBy('skor_akhir', 'desc')
            ->get();
    }

    private function semuaPesertaUntukExport()
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif) {
            return collect();
        }

        return Peserta::where('tahun_seleksi_id', $periodeAktif->id)
            ->orderBy('nama_lengkap', 'asc')
            ->get();
    }

    public function rekapNilai()
    {
        $pesertas = $this->hitungDanUrutkanPeserta();
        return view('panel.laporan.rekap-nilai', ['pesertas' => $pesertas]);
    }

    public function liveRanking()
    {
        $pesertas = $this->hitungDanUrutkanPeserta();
        $topPerformer = $pesertas->first();

        return view('panel.laporan.live-ranking', [
            'pesertas' => $pesertas,
            'topPerformer' => $topPerformer,
        ]);
    }

    public function showDetail(Peserta $peserta, $peringkat)
    {
        $peserta->load('berkas', 'penilaians.juri');

        $penilaianGk = $peserta->penilaians->filter(function ($penilaian) {
            return $penilaian->juri->jenis_juri === 'GK';
        });
        $penilaianBi = $peserta->penilaians->filter(function ($penilaian) {
            return $penilaian->juri->jenis_juri === 'BI';
        });

        $berkasCu = $peserta->berkas->where('jenis_berkas', 'CU');
        $berkasGk = $peserta->berkas->where('jenis_berkas', 'NASKAH_GK')->first();
        $berkasSlideGk = $peserta->berkas->where('jenis_berkas', 'SLIDE_GK')->first();

        $kriteriaGkMap = [
            '1_1' => 'Penggunaan Bahasa', '1_2' => 'Kesesuaian Pengutipan', '2_1' => 'Fakta/gejala',
            '2_2' => 'Identifikasi masalah', '2_3' => 'Rumusan masalah', '2_4' => 'Akibat pembiaran',
            '2_5' => 'Solusi SMART', '2_6' => 'Dampak lanjutan', '2_7' => 'Langkah tindakan',
            '2_8' => 'Kendala & antisipasi', '3_1' => 'Keunikan & Orisinalitas', '3_2' => 'Keterlaksanaan Gagasan',
        ];

        $kriteriaBiMap = [
            'content' => 'Content (Isi Materi)', 'accuracy' => 'Accuracy (Tata Bahasa & Kosakata)',
            'fluency' => 'Fluency (Kelancaran)', 'pronunciation' => 'Pronunciation (Pengucapan)',
            'performance' => 'Overall Performance (Sikap & Gestur)',
        ];

        return view('panel.laporan.detail-rekap', [
            'peserta' => $peserta, 
            'peringkat' => $peringkat, 
            'berkasCu' => $berkasCu,
            'berkasGk' => $berkasGk, 
            'berkasSlideGk' => $berkasSlideGk,
            'kriteriaGkMap' => $kriteriaGkMap, 
            'kriteriaBiMap' => $kriteriaBiMap,
            'penilaianGk' => $penilaianGk,
            'penilaianBi' => $penilaianBi,
        ]);
    }

    public function exportRekapNilai()
    {
        $pesertas = $this->semuaPesertaUntukExport();
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        $fileName = 'Rekap-Nilai-Pilmapres-' . ($periodeAktif->tahun ?? 'data') . '.xlsx';

        return Excel::download(new RekapNilaiExport($pesertas, $periodeAktif), $fileName);
    }

    public function exportRiwayatPeriode(TahunSeleksi $tahunSeleksi)
    {
        $pesertas = Peserta::where('tahun_seleksi_id', $tahunSeleksi->id)
            ->orderBy('nama_lengkap', 'asc')
            ->get();
        $fileName = 'Rekap-Nilai-Pilmapres-Riwayat-' . ($tahunSeleksi->tahun ?? 'data') . '.xlsx';

        return Excel::download(new RiwayatPeriodeExport($pesertas, $tahunSeleksi), $fileName);
    }

    public function exportRekapNilaiZip()
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif) {
            return redirect()->back()->with('notification', ['type' => 'danger', 'message' => 'Tidak ada periode aktif untuk diekspor.']);
        }

        $pesertas = $this->semuaPesertaUntukExport();
        $namaFolder = 'Arsip-Pilmapres-' . $periodeAktif->tahun;
        $namaZip = $namaFolder . '.zip';
        $pathFolderTemp = storage_path('app/temp/' . $namaFolder);

        if (!File::isDirectory($pathFolderTemp)) {
            File::makeDirectory($pathFolderTemp, 0755, true, true);
        }

        $namaFileExcel = 'Rekap-Nilai-Pilmapres-' . $periodeAktif->tahun . '.xlsx';
        Excel::store(new RekapNilaiExport($pesertas, $periodeAktif), $namaFolder . '/' . $namaFileExcel, 'temp');

        foreach ($pesertas as $peserta) {
            $folderPeserta = $pathFolderTemp . '/' . $peserta->nama_lengkap . ' - ' . $peserta->nim;
            if (!File::isDirectory($folderPeserta)) {
                File::makeDirectory($folderPeserta, 0755, true, true);
            }

            if ($peserta->foto_path && File::exists(storage_path('app/public/' . $peserta->foto_path))) {
                File::copy(storage_path('app/public/' . $peserta->foto_path), $folderPeserta . '/Foto Profil.' . pathinfo($peserta->foto_path, PATHINFO_EXTENSION));
            }

            foreach ($peserta->berkas as $berkas) {
                if ($berkas->path_file && File::exists(storage_path('app/public/' . $berkas->path_file))) {
                    File::copy(storage_path('app/public/' . $berkas->path_file), $folderPeserta . '/' . $berkas->nama_berkas . '.' . pathinfo($berkas->path_file, PATHINFO_EXTENSION));
                }
            }
        }

        $zip = new ZipArchive;
        if ($zip->open(storage_path('app/temp/' . $namaZip), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = File::allFiles($pathFolderTemp);
            foreach ($files as $file) {
                $relativePath = substr($file->getPathname(), strlen($pathFolderTemp) + 1);
                $zip->addFile($file->getPathname(), $relativePath);
            }
            $zip->close();
        }

        File::deleteDirectory($pathFolderTemp);

        return response()->download(storage_path('app/temp/' . $namaZip))->deleteFileAfterSend(true);
    }
}
