<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $action;
    public $description;

    public function __construct(?User $user, $action, $description)
    {
        $this->user = $user;
        $this->action = $action;
        $this->description = $description;
    }
}