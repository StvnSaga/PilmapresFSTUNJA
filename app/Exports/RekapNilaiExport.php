<?php

namespace App\Exports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapNilaiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $pesertas;
    protected $peringkat = 0;

    public function __construct($pesertas)
    {
        $this->pesertas = $pesertas;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->pesertas;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Peringkat',
            'Nama Lengkap',
            'NIM',
            'Program Studi',
            'Nilai CU',
            'Nilai GK',
            'Nilai BI',
            'Nilai Akhir',
        ];
    }

    /**
     * @param Peserta $peserta
     * @return array
     */
    public function map($peserta): array
    {
        $this->peringkat++;
        return [
            $this->peringkat,
            $peserta->nama_lengkap,
            $peserta->nim,
            $peserta->prodi,
            number_format($peserta->total_skor_cu, 2),
            number_format($peserta->total_skor_gk, 2),
            number_format($peserta->total_skor_bi, 2),
            number_format($peserta->skor_akhir, 2),
        ];
    }
}