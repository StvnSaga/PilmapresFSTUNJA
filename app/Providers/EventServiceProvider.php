<?php

namespace App\Providers;

use App\Models\Penilaian;
use App\Models\Peserta;
use App\Models\TahunSeleksi;
use App\Models\User;
use App\Observers\PenilaianObserver;
use App\Observers\PesertaObserver;
use App\Observers\TahunSeleksiObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
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
        // Mendaftarkan semua model observer.
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
