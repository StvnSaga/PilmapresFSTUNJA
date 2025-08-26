<?php

namespace App\Observers;

use App\Models\Penilaian;
use App\Events\LogActivityEvent;
use Illuminate\Support\Facades\Auth;

class PenilaianObserver
{
    /**
     * Handle the Penilaian "updated" event.
     * Kita menggunakan 'updated' karena Penilaian dibuat kosong dulu, baru diisi skor.
     */
    public function updated(Penilaian $penilaian): void
    {
        $juri = Auth::user();
        $peserta = $penilaian->peserta; // Ambil relasi peserta

        // Cek apakah skor GK yang berubah
        if ($penilaian->isDirty('total_skor_gk')) {
            $skorLama = $penilaian->getOriginal('total_skor_gk');
            $skorBaru = $penilaian->total_skor_gk;

            if ($skorLama === null) {
                // Jika skor lama adalah NULL, berarti ini pertama kali dinilai
                $deskripsi = "Memberikan nilai Gagasan Kreatif (GK) sebesar {$skorBaru} untuk peserta \"" . $peserta->nama_lengkap . "\".";
                LogActivityEvent::dispatch($juri, 'submit_score_gk', $deskripsi);
            } else {
                // Jika skor lama ada nilainya, berarti ini pengubahan nilai
                $deskripsi = "Mengubah nilai Gagasan Kreatif (GK) untuk peserta \"" . $peserta->nama_lengkap . "\" dari {$skorLama} menjadi {$skorBaru}.";
                LogActivityEvent::dispatch($juri, 'update_score_gk', $deskripsi);
            }
        }

        // Cek apakah skor BI yang berubah
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
