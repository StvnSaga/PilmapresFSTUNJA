<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\TahunSeleksi;

class RiwayatPeriodeExport implements FromView
{
    public function __construct(protected $pesertas, protected $tahunSeleksi)
    {
    }

    public function view(): View
    {
        return view('panel.exports.rekap-nilai-riwayat', [
            'pesertas' => $this->pesertas,
            'periodeAktif' => $this->tahunSeleksi,
        ]);
    }
}