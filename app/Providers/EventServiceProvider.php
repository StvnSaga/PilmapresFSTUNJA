<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Import semua Model dan Observer yang kita gunakan
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Peserta;
use App\Observers\PesertaObserver;
use App\Models\TahunSeleksi;
use App\Observers\TahunSeleksiObserver;
use App\Models\Penilaian;
use App\Observers\PenilaianObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Di sinilah kita mendaftarkan semua Observer kita
        User::observe(UserObserver::class);
        Peserta::observe(PesertaObserver::class);
        TahunSeleksi::observe(TahunSeleksiObserver::class);
        Penilaian::observe(PenilaianObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
