<?php

namespace App\Models;

use Auth;
use App\Traits\VoteCount;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model {

	use SoftDeletes;
	use Searchable;
	use VoteCount;

	protected $table = 'video';

	protected $fillable = [
		'uid',
		'title',
		'description',
		'processed',
		'online_video_id',
		'online_video_filename',
		'visibility',
		'allows_vote',
		'allows_comment',
		'processed_percentage',
		'channel_id',
		'price',
		'type',
	];

	protected $appends = [
		'video_url',
		'thumbnail',
		'is_private',
		'video_processed',
		'is_owned_by_current_user',
		'can_be_accessed_by_current_user',
		'views',
		'votes',
		'comments',
		'has_current_user_bought_video_channel',
	];

	protected $casts = [
		'price' => 'decimal',
	];

	public function toSearchableArray() {
		$properties = $this->toArray();

		$properties['visible'] = $this->isVisible();

		return $properties;
	}

	public function getRouteKeyName() {
		return 'uid';
	}

	public function channel() {
		return $this->belongsTo(Channel::class);
	}

	public function views() {
		return $this->hasMany(VideoView::class);
	}

	public function votes() {
		return $this->morphMany(Vote::class, 'votable');
	}

	public function comments() {
		return $this->morphMany(Comment::class, 'commentable')->where('parent_id', null);
	}

	public function getThumbnailAttribute() {
		return $this->getThumbnail();
	}

	public function getVideoProcessedAttribute() {
		return (bool) $this->isProcessed();
	}

	public function getIsPrivateAttribute() {
		return $this->isPrivate();
	}

	public function getIsOwnedByCurrentUserAttribute() {
		return $this->ownedByCurrentUser();
	}

	public function getCanBeAccessedByCurrentUserAttribute() {
		return $this->canBeAccessedByCurrentUser();
	}

	public function getVideoUrlAttribute() {
		return $this->getVideoUrl();
	}

	public function getViewsAttribute() {
		return $this->getViewCount();
	}

	public function getVotesAttribute() {
		return $this->votes()->count();
	}

	public function getCommentsAttribute() {
		return $this->comments()->count();
	}

	public function getHasCurrentUserBoughtVideoChannelAttribute() {
		if (Auth::user()) {
			return $this->hasCurrentUserBoughtVideoChannel();
		}

		return false;
	}

	public function isProcessed() {
		return $this->processed;
	}

	public function getThumbnail() {
		if (!$this->isProcessed()) {
			return config('myengine.cdn.cloudfront.videos') . 'default.jpg';
		}

		return config('myengine.cdn.cloudfront.videos') . $this->online_video_id . '_1.jpg';
	}

	public function getVideoUrl() {
		return config('myengine.cdn.cloudfront.videos') . $this->online_video_id . '.mp4';
	}

	public function isPrivate() {
		return $this->visibility === 'private';
	}

	public function isPublic() {
		return $this->visibility === 'public';
	}

	public function isVisible() {
		return $this->isProcessed() && $this->isPublic();
	}

	public function ownedByCurrentUser() {
		if (Auth::guest()) {
			return false;
		}

		return $this->channel->user_id === Auth::user()->id;
	}

	public function canBeAccessedByCurrentUser() {

		if ($this->ownedByCurrentUser()) {
			return true;
		} elseif (!$this->ownedByCurrentUser() && !$this->isPrivate() && $this->isFree()) {
			return true;
		} else if (!$this->ownedByCurrentUser() && !$this->isPrivate() && $this->isPaid() && $this->hasCurrentUserBoughtVideoChannel()) {
			return true;
		}

		return false;
	}

	public function getViewCount() {
		return $this->views()->count();
	}

	public function canVote() {
		return (bool) $this->allows_vote;
	}

	public function canComment() {
		return (bool) $this->allows_comment;
	}

	public  function getUserVoteType(User $user = null) {
		if (!$user) {
			return null;
		}

		$userVote = $this->votes()->where('user_id', $user->id)->first();

		if (!$userVote) {
			return null;
		}

		return $userVote->type;
	}

	public function scopeProcessed($query) {
		return $query->where('video.processed', true);
	}

	public function scopePublic($query) {
		return $query->where('video.visibility', 'public');
	}

	public function scopeFree($query) {
		return $query->where('video.type', 'free');
	}

	public function scopeVisible($query) {
		return $query->processed()->public();
	}

	public function isFree() {
		return $this->type === 'free';
	}

	public function isPaid() {
		return $this->type === 'paid';
	}

	public function hasCurrentUserBoughtVideoChannel() {
		// To make sure if the user has bought the channel to which this video belongs
		if (Auth::user()) {
			$channel_id = $this->channel->id;
			return (bool) Auth::user()->bought()->where('item_id', $channel_id)->count();
		}

		return false;
	}

}