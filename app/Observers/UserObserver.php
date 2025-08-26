<?php

namespace App\Observers;

use App\Models\User;
use App\Events\LogActivityEvent;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // PERBAIKAN: Cek apakah ada user yang login. Jika tidak, gunakan null.
        $actor = Auth::user();
        $deskripsi = 'User baru "' . $user->name . '" ditambahkan.';

        // Jika user dibuat oleh seeder/sistem, aktornya adalah user itu sendiri
        if (!$actor) {
            $actor = $user;
            $deskripsi = 'Akun "' . $user->name . '" dibuat oleh sistem (seeder).';
        }

        LogActivityEvent::dispatch($actor, 'create_user', $deskripsi);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $perubahan = [];
        foreach ($user->getChanges() as $key => $value) {
            if ($key === 'updated_at' || $key === 'password' || $key === 'remember_token') {
                continue;
            }
            $oldValue = $user->getOriginal($key);
            $perubahan[] = "mengubah '{$key}' dari '{$oldValue}' menjadi '{$value}'";
        }

        if (count($perubahan) > 0) {
            $deskripsi = 'Memperbarui data user "' . $user->name . '": ' . implode(', ', $perubahan) . '.';
            LogActivityEvent::dispatch(Auth::user(), 'update_user', $deskripsi);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        LogActivityEvent::dispatch(Auth::user(), 'delete_user', 'Menghapus user "' . $user->name . '" (Email: ' . $user->email . ').');
    }
}
