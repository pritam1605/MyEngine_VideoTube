<?php

namespace App\Events;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChannelSold {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $seller;
    public $channel;

    public function __construct(User $seller, Channel $channel) {
        $this->seller = $seller;
        $this->channel = $channel;
    }
}
