<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamJejak extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_seleksi_id',
        'peserta_id',
        'peringkat',
        'deskripsi_singkat',
        'foto_path',
    ];

    public function tahunSeleksi()
    {
        return $this->belongsTo(TahunSeleksi::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}