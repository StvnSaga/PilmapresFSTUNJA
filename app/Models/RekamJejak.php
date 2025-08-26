<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamJejak extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tahun_seleksi_id',
        'peserta_id',
        'peringkat',
        'deskripsi_singkat',
        'foto_path',
    ];

    /**
     * Mendefinisikan relasi ke model TahunSeleksi.
     */
    public function tahunSeleksi()
    {
        return $this->belongsTo(TahunSeleksi::class);
    }

    /**
     * Mendefinisikan relasi ke model Peserta.
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}
