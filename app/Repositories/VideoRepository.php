<?php

namespace App\Repositories;

use Auth;
use Cache;
use App\Models\User;
use App\Models\Video;
use App\Models\Channel;

class VideoRepository {

	public function getUserVideosFromSubscriptions(User $user = null, $limit = 100) {
		if ($user) {
			$videos = $user->subscribedChannels()->with(['videos' => function($query) use ($limit) {
				return $query->visible()->take($limit);
			}])->get()->pluck('videos')->flatten()->sortByDesc('created_at')->values()->all();

			if (!$videos || count($videos) < 8) {
				$videos_to_be_igonred = collect($videos)->pluck('id')->toArray();
				$videos = array_merge($videos, $this->getVideosFromDefaultSubscription(15, $videos_to_be_igonred)->toArray());
			}
		} else {
			$videos = $this->getVideosFromDefaultSubscription()->toArray();
		}

		return $videos;
	}

	public function getVideosFromDefaultSubscription($limit = 50, $remove_duplicate = null) {
		// public videos from all the channels
		$videos = Cache::remember('videos_from_default_subscription', 1, function() use ($limit, $remove_duplicate) {
			return Video::with('channel')
	                    ->visible()
	                    ->when($remove_duplicate, function($query) use ($remove_duplicate) {
							return $query->whereNotIn('id', $remove_duplicate);
	                    })
				        ->latest()
				        ->limit($limit)
				        ->get();
		});

		return $videos;
	}

	public function getUserVideos(User $user, $limit = 100) {

		/**********************Disabling cache for the time being********************************/
		// $user_videos = Cache::remember("user_videos_{$user->username}", 1, function() use ($user, $limit) {
		// 	return $user->videos()->latest()->take($limit)->get();
		// });
		// return $user_videos;

		return $user->videos()->latest()->take($limit)->get();
	}

	public function getVideosForSidePane(Video $video) {
		if (Auth::user()) {
			return $this->getSidePaneVideosForAuthorizedUsers($video);
		}

		return $this->getSidePaneVideosForUnauthorizedUsers($video);
	}

	public function getSidePaneVideosForAuthorizedUsers(Video $video, $limit = 10) {

		$side_pane_videos = Cache::remember("side_pane_videos_for_authorized_users_with_video_id_{$video->id}", 1, function() use ($video, $limit) {
			$channel = $video->channel;
			$videos = $channel->videos()
		                      ->where([
								['uid', '!=', $video->uid],
								['channel_id', '=', $video->channel_id],])
						      ->visible()->oldest()->limit(count($channel->videos))->get();

			if ((count($videos) <= 3)  || $channel->isFree()) {
				if (count($videos) < 10) {
					$videos = $videos->merge($this->getSidePaneVideosForUnauthorizedUsers($video));
				}

				return $videos->slice(0, 10);
			}

			return $videos;

		});

		return $side_pane_videos;
	}

	public function getSidePaneVideosForUnauthorizedUsers(Video $video, $limit = 10) {
		$videos = Cache::remember("side_pane_videos_for_unauthorized_users_with_video_id_{$video->id}", 1, function() use ($video, $limit) {
			return Video::with('channel')->where('uid', '!=', $video->uid)
										 ->visible()->latest()->limit($limit)->get();
		});

		return$videos;
	}

	public function getChannelVideos(Channel $channel, $limit = 50) {
		// to display videos on the channel dashboard
		return $channel->videos()
					   ->with('channel')
		               ->processed()
		               ->when(!$channel->is_owned_by_current_user, function($query) use ($channel) {
							return $query->public();
						})
		               ->oldest()
		               ->limit($limit)
		               ->get();
	}
}