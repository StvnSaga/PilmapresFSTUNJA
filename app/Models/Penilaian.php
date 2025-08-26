<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Penilaian extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'peserta_id',
        'juri_id',
        'skor_gk_detail',
        'total_skor_gk',
        'catatan_juri_gk',
        'catatan_gk_detail',
        'skor_bi_detail',
        'total_skor_bi',
        'catatan_juri_bi',
        'catatan_bi_detail',
        'status_penilaian_gk', // Status GK ada di sini
        'status_penilaian_bi', // Status BI ada di sini
    ];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'skor_gk_detail' => 'array',
        'catatan_gk_detail' => 'array',
        'skor_bi_detail' => 'array',
        'catatan_bi_detail' => 'array',
    ];

    // !! TAMBAHKAN FUNGSI RELASI BARU INI !!
    /**
     * Mendefinisikan relasi bahwa Penilaian ini 'milik' satu Juri (User).
     */
    public function juri()
    {
        // Relasi ke Model User, karena juri adalah salah satu role di tabel users
        return $this->belongsTo(User::class, 'juri_id');
    }

    /**
     * Mendefinisikan relasi bahwa Penilaian ini 'milik' satu Peserta.
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    /**
     * Accessor untuk memeriksa apakah skor GK sudah diisi.
     */
    protected function sudahDinilaiGk(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_skor_gk !== null
        );
    }

    /**
     * Accessor untuk memeriksa apakah skor BI sudah diisi.
     */
    protected function sudahDinilaiBi(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_skor_bi !== null
        );
    }
}
