<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapNilaiExport implements FromView
{
    public function __construct(protected $pesertas, protected $periodeAktif)
    {
    }

    public function view(): View
    {
        return view('panel.exports.rekap-nilai', [
            'pesertas' => $this->pesertas,
            'periodeAktif' => $this->periodeAktif,
        ]);
    }
}
