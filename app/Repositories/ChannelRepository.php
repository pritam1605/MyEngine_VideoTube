<?php

namespace App\Repositories;

use Auth;
use Cache;
use App\Models\User;
use App\Models\Video;
use App\Models\Channel;

class ChannelRepository {

	public function getUserChannels(User $user) {

		return $user->channels()
					->when(Auth::guest() || Auth::user()->id !== $user->id, function($query) {
						$query->public();
					})
		            ->latest()
		            ->get();
	}
}