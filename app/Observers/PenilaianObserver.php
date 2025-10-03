<?php

namespace App\Observers;

use App\Events\LogActivityEvent;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;

class PenilaianObserver
{
    /**
     * Menangani event 'updated' pada model Penilaian.
     * Event ini digunakan karena skor diisi setelah record Penilaian awalnya dibuat kosong.
     */
    public function updated(Penilaian $penilaian): void
    {
        $juri = Auth::user();
        $peserta = $penilaian->peserta;

        if ($penilaian->isDirty('total_skor_gk')) {
            $skorLama = $penilaian->getOriginal('total_skor_gk');
            $skorBaru = $penilaian->total_skor_gk;

            if ($skorLama === null) {
                $deskripsi = "Memberikan nilai Gagasan Kreatif (GK) sebesar {$skorBaru} untuk peserta \"" . $peserta->nama_lengkap . "\".";
                LogActivityEvent::dispatch($juri, 'submit_score_gk', $deskripsi);
            } else {
                $deskripsi = "Mengubah nilai Gagasan Kreatif (GK) untuk peserta \"" . $peserta->nama_lengkap . "\" dari {$skorLama} menjadi {$skorBaru}.";
                LogActivityEvent::dispatch($juri, 'update_score_gk', $deskripsi);
            }
        }

        if ($penilaian->isDirty('total_skor_bi')) {
            $skorLama = $penilaian->getOriginal('total_skor_bi');
            $skorBaru = $penilaian->total_skor_bi;

            if ($skorLama === null) {
                $deskripsi = "Memberikan nilai Bahasa Inggris (BI) sebesar {$skorBaru} untuk peserta \"" . $peserta->nama_lengkap . "\".";
                LogActivityEvent::dispatch($juri, 'submit_score_bi', $deskripsi);
            } else {
                $deskripsi = "Mengubah nilai Bahasa Inggris (BI) untuk peserta \"" . $peserta->nama_lengkap . "\" dari {$skorLama} menjadi {$skorBaru}.";
                LogActivityEvent::dispatch($juri, 'update_score_bi', $deskripsi);
            }
        }
    }
}
