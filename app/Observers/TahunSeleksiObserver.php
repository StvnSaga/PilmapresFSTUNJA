<?php

namespace App\Observers;

use App\Models\TahunSeleksi;
use App\Events\LogActivityEvent;
use Illuminate\Support\Facades\Auth;

class TahunSeleksiObserver
{
    public function created(TahunSeleksi $tahunSeleksi): void
    {
        LogActivityEvent::dispatch(Auth::user(), 'create_periode', 'Membuat periode seleksi baru untuk tahun ' . $tahunSeleksi->tahun . '.');
    }

    public function updated(TahunSeleksi $tahunSeleksi): void
    {
        if ($tahunSeleksi->isDirty('is_active') && $tahunSeleksi->is_active) {
            LogActivityEvent::dispatch(Auth::user(), 'activate_periode', 'Menjadikan periode ' . $tahunSeleksi->tahun . ' sebagai periode aktif.');
        }

        if ($tahunSeleksi->isDirty('status')) {
            $oldStatus = $tahunSeleksi->getOriginal('status');
            $newStatus = $tahunSeleksi->status;
            $tahun = $tahunSeleksi->tahun;

            if ($newStatus == 'penilaian') {
                LogActivityEvent::dispatch(Auth::user(), 'start_penilaian', "Memulai tahap penilaian untuk periode {$tahun}.");
            } elseif ($newStatus == 'selesai') {
                LogActivityEvent::dispatch(Auth::user(), 'end_periode', "Mengakhiri dan mengarsipkan periode {$tahun}.");
            }
        }
    }
}
