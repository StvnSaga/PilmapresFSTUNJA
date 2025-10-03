<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Peserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_seleksi_id',
        'nama_lengkap',
        'nim',
        'prodi',
        'angkatan',
        'no_hp',
        'ipk',
        'email',
        'foto_path',
        'status_verifikasi',
        'total_skor_cu',
        'total_skor_gk',
        'total_skor_bi',
        'skor_akhir',
    ];

    protected function berkasLengkap(): Attribute
    {
        return Attribute::make(
            get: function () {
                $berkasWajibList = ['KTP', 'KRS', 'NASKAH_GK', 'SLIDE_GK'];
                $this->loadMissing('berkas');
                
                foreach ($berkasWajibList as $jenis) {
                    if (!$this->berkas->where('jenis_berkas', $jenis)->isNotEmpty()) {
                        return false;
                    }
                }
                return true;
            }
        );
    }

    public function berkas()
    {
        return $this->hasMany(Berkas::class);
    }
    
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    protected function nilaiCuBerbobot(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->total_skor_cu ?? 0) * 0.45
        );
    }

    protected function nilaiGkBerbobot(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->total_skor_gk ?? 0) * 0.35
        );
    }

    protected function nilaiBiBerbobot(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->total_skor_bi ?? 0) * 0.20
        );
    }

    public static function getRankedListForActivePeriod()
    {
        $periodeAktif = TahunSeleksi::where('is_active', true)->first();
        if (!$periodeAktif) {
            return collect();
        }

        $pesertasDiPeriodeIni = self::where('tahun_seleksi_id', $periodeAktif->id)
                                    ->where('status_verifikasi', 'diverifikasi')
                                    ->get();

        foreach ($pesertasDiPeriodeIni as $peserta) {
            $totalCu = $peserta->berkas()->where('jenis_berkas', 'CU')->sum('skor');
            $avgGk = $peserta->penilaians()->avg('total_skor_gk');
            $avgBi = $peserta->penilaians()->avg('total_skor_bi');
            $bobotCu = 0.45;
            $bobotGk = 0.35;
            $bobotBi = 0.20;
            $skorAkhir = (($totalCu ?? 0) * $bobotCu) + (($avgGk ?? 0) * $bobotGk) + (($avgBi ?? 0) * $bobotBi);

            $peserta->total_skor_cu = $totalCu ?? 0;
            $peserta->total_skor_gk = $avgGk ?? 0;
            $peserta->total_skor_bi = $avgBi ?? 0;
            $peserta->skor_akhir = $skorAkhir;
            $peserta->save();
        }

        return self::where('tahun_seleksi_id', $periodeAktif->id)
            ->where('status_verifikasi', 'diverifikasi')
            ->orderBy('skor_akhir', 'desc')
            ->get();
    }
}