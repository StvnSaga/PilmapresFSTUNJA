<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berkas extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_id',
        'nama_berkas',
        'jenis_berkas',
        'tingkat',
        'cu_bidang',
        'cu_wujud',
        'path_file',
        'skor',
        'status_penilaian',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}