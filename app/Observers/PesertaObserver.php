<?php

namespace App\Observers;

use App\Models\Peserta;
use App\Events\LogActivityEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- Jangan lupa import Storage

class PesertaObserver
{
    /**
     * Handle the Peserta "created" event.
     */
    public function created(Peserta $peserta): void
    {
        LogActivityEvent::dispatch(Auth::user(), 'create_peserta', 'Menambahkan peserta baru "' . $peserta->nama_lengkap . '".');
    }

    /**
     * Handle the Peserta "updating" event.
     */
    public function updating(Peserta $peserta): void
    {
        // Cek apakah kolom 'status_verifikasi' yang berubah
        if ($peserta->isDirty('status_verifikasi')) {
            $oldStatus = $peserta->getOriginal('status_verifikasi');
            $newStatus = $peserta->status_verifikasi;
            $namaPeserta = '"' . $peserta->nama_lengkap . '"';

            if ($newStatus == 'diverifikasi') {
                LogActivityEvent::dispatch(Auth::user(), 'validate_peserta', "Memvalidasi pendaftaran untuk peserta {$namaPeserta}.");
            } elseif ($newStatus == 'ditolak') {
                LogActivityEvent::dispatch(Auth::user(), 'reject_peserta', "Menolak pendaftaran untuk peserta {$namaPeserta}.");
            } elseif ($oldStatus == 'diverifikasi' && $newStatus == 'menunggu') {
                $jumlahNilai = $peserta->penilaians()->count();
                $deskripsi = "Membatalkan verifikasi untuk peserta {$namaPeserta}.";
                if ($jumlahNilai > 0) {
                    $deskripsi .= " ({$jumlahNilai} data penilaian dari juri ikut terhapus).";
                }
                LogActivityEvent::dispatch(Auth::user(), 'unverify_peserta', $deskripsi);
            }
        }
    }

    /**
     * Handle the Peserta "deleting" event.
     * Dijalankan TEPAT SEBELUM peserta dihapus dari database.
     */
    public function deleting(Peserta $peserta): void
    {
        // 1. Hapus foto profil utama peserta
        if ($peserta->foto_path) {
            Storage::disk('public')->delete($peserta->foto_path);
        }

        // 2. Hapus semua berkas terkait (KTP, KRS, CU, dll.)
        // Kita muat relasi 'berkas' untuk memastikan datanya ada
        $peserta->load('berkas');
        foreach ($peserta->berkas as $berkas) {
            if ($berkas->path_file) {
                Storage::disk('public')->delete($berkas->path_file);
            }
        }
    }

    /**
     * Handle the Peserta "deleted" event.
     * Dijalankan SETELAH peserta dihapus dari database.
     */
    public function deleted(Peserta $peserta): void
    {
        LogActivityEvent::dispatch(Auth::user(), 'delete_peserta', 'Menghapus peserta "' . $peserta->nama_lengkap . '" (NIM: ' . $peserta->nim . ').');
    }
}
