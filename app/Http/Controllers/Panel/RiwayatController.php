<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\TahunSeleksi;
use Illuminate\Http\Request;
use App\Exports\RiwayatPeriodeExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File; // Import File
use ZipArchive; // Import ZipArchive

class RiwayatController extends Controller
{
    public function index()
    {
        // PERUBAHAN UTAMA: Tambahkan ->where('status', 'selesai')
        $periodeSelesai = TahunSeleksi::where('status', 'selesai')
                                      ->orderBy('tahun', 'desc')
                                      ->get();
        
        $riwayat = [];

        foreach ($periodeSelesai as $periode) {
            $pemenang = $periode->pesertas()->orderBy('skor_akhir', 'desc')->first();

            $riwayat[] = [
                'tahun' => $periode->tahun,
                'pemenang' => $pemenang ? $pemenang->nama_lengkap : 'Belum Ada Pemenang',
            ];
        }

        return view('panel.riwayat.periode-index', [
            'riwayat' => $riwayat,
        ]);
    }

    public function periodeDetail($tahun)
    {
        $periode = TahunSeleksi::where('tahun', $tahun)->firstOrFail();
        $pesertas = $periode->pesertas()->orderBy('skor_akhir', 'desc')->get();

        $pemenang = $pesertas->first();

        return view('panel.riwayat.periode-detail', [
            'periode' => $periode,
            'pesertas' => $pesertas,
            'pemenang' => $pemenang,
        ]);
    }

    public function exportPeriodeDetail($tahun)
    {
        $periode = TahunSeleksi::where('tahun', $tahun)->firstOrFail();
        $pesertas = $periode->pesertas()->orderBy('skor_akhir', 'desc')->get();
        $fileName = 'Laporan-Riwayat-Pilmapres-' . $tahun . '.xlsx';

        return Excel::download(new RiwayatPeriodeExport($pesertas), $fileName);
    }

        public function exportPeriodeDetailZip($tahun)
    {
        $periode = TahunSeleksi::where('tahun', $tahun)->firstOrFail();
        $pesertas = $periode->pesertas()->orderBy('skor_akhir', 'desc')->get();
        
        $namaFolder = 'Arsip-Pilmapres-' . $tahun;
        $namaZip = $namaFolder . '.zip';
        $pathFolderTemp = storage_path('app/temp/' . $namaFolder);

        if (!File::isDirectory($pathFolderTemp)) {
            File::makeDirectory($pathFolderTemp, 0755, true, true);
        }

        $namaFileExcel = 'Rekap-Nilai-Pilmapres-' . $tahun . '.xlsx';
        Excel::store(new RiwayatPeriodeExport($pesertas), $namaFolder . '/' . $namaFileExcel, 'temp');

        foreach ($pesertas as $peserta) {
            $folderPeserta = $pathFolderTemp . '/' . $peserta->nama_lengkap . ' - ' . $peserta->nim;
            if (!File::isDirectory($folderPeserta)) File::makeDirectory($folderPeserta, 0755, true, true);
            
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
