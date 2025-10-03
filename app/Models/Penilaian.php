<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Penilaian extends Model
{
    use HasFactory;

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
        'status_penilaian_gk',
        'status_penilaian_bi',
    ];

    protected $casts = [
        'skor_gk_detail' => 'array',
        'catatan_gk_detail' => 'array',
        'skor_bi_detail' => 'array',
        'catatan_bi_detail' => 'array',
    ];

    public function juri()
    {
        return $this->belongsTo(User::class, 'juri_id');
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    protected function sudahDinilaiGk(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_skor_gk !== null
        );
    }

    protected function sudahDinilaiBi(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_skor_bi !== null
        );
    }
}