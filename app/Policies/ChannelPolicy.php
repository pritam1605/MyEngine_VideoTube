<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChannelPolicy {

    public function edit(User $user, Channel $channel) {
        return $user->id === $channel->user_id;
    }

    public function update(User $user, Channel $channel) {
        return $user->id === $channel->user_id;
    }

    public function subscribe(User $user, Channel $channel) {
        if ($user->isUserSubscribedTo($channel)) {
            return false;
        }

    	return !$user->ownsChannel($channel);
    }

    public function unsubscribe(User $user, Channel $channel) {
    	return $user->isUserSubscribedTo($channel);
    }
}
