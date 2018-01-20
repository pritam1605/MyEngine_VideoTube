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

class ChannelPurchased {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $buyer;
    public $channel;

    public function __construct(User $buyer, Channel $channel) {
        $this->buyer = $buyer;
        $this->channel = $channel;
    }
}