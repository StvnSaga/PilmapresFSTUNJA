<?php

namespace App\Listeners;

use App\Events\LogActivityEvent;
use App\Models\LogActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreLogActivityListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\LogActivityEvent $event
     * @return void
     */
    public function handle(LogActivityEvent $event): void
    {
        LogActivity::create([
            'user_id' => $event->user->id,
            'action' => $event->action,
            'description' => $event->description,
        ]);
    }
}
