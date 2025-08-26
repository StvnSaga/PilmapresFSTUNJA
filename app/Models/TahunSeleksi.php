<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunSeleksi extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tahun',
        'status',
        'is_active',
    ];

    public function pesertas()
    {
        return $this->hasMany(Peserta::class);
    }
}