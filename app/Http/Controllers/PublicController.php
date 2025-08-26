<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\RekamJejak;
use Illuminate\Http\Request;

class PublicController extends Controller
{   
    
    public function papanPeringkat()
    {
        // Panggil method dari model yang sudah kita buat
        $pesertas = Peserta::getRankedListForActivePeriod();

        // Siapkan data untuk view
        $top3 = $pesertas->slice(0, 3)->values();
        $others = $pesertas->slice(3)->values();
            
        // Nama view Anda adalah 'papan-peringkat', bukan 'panel.laporan.live-ranking'
        return view('front.papan-peringkat', [
            'pesertas' => $pesertas,
            'top3' => $top3,
            'others' => $others,
        ]);
    }

    public function home()
    {
        // Ambil semua rekam jejak, eager load relasi untuk efisiensi
        $rekamJejak = RekamJejak::with('peserta', 'tahunSeleksi')
                                ->orderBy('peringkat') // Urutkan berdasarkan peringkat
                                ->get();

        // !! PERUBAHAN UTAMA ADA DI SINI !!
        // Kelompokkan hasil berdasarkan tahun seleksi menggunakan relasi
        $rekamJejakGrouped = $rekamJejak->groupBy(function ($item) {
            // Gunakan tahun dari relasi sebagai kunci grup
            return $item->tahunSeleksi->tahun;
        })->sortByDesc(function ($group, $tahun) {
            // Urutkan grup berdasarkan tahun (kunci)
            return $tahun;
        });
        
        return view('front.home', ['rekamJejakGrouped' => $rekamJejakGrouped]);
    }
}
    