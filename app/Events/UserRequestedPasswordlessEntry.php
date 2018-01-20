<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRequestedPasswordlessEntry {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $remember_me;

    public function __construct(User $user, $remember_me = false) {
        $this->user = $user;
        $this->remember_me = $remember_me;
    }
}
