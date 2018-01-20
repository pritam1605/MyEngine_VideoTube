<?php

namespace App\Models;

use Auth;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model {

	use Searchable;

	protected $table = 'channel';

	protected $fillable = [
		'name',
		'slug',
		'description',
		'visibility',
		'type',
		'image_filename',
		'is_default',
		'price',
	];

	protected $appends = [
		'imageUrl',
		'is_owned_by_current_user',
		'total_subscriptions',
		'total_video_views',
		'total_video_count',
		'has_current_user_bought_this_channel',
	];

	protected $casts = [
		'price' => 'decimal',
	];

	public function toSearchableArray() {
		$properties = $this->toArray();

		$properties['visible'] = $this->isVisible();
		$properties['free'] = $this->isFree();

		return $properties;
	}

	public function getRouteKeyName() {
		return 'slug';
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function videos() {
		return $this->hasMany(Video::class);
	}

	public function subscriptions() {
		return $this->hasMany(Subscription::class);
	}

	public function getImageUrlAttribute() {
		return $this->getImageUrl();
	}

	public function getImageUrl() {
		if (!$this->image_filename) {
			return config('myengine.cdn.cloudfront.images') . 'channel/default.png';
		}

		return config('myengine.cdn.cloudfront.images') . 'channel/' . $this->image_filename;
	}

	public function getIsOwnedByCurrentUserAttribute() {
		return $this->ownedByCurrentUser();
	}

	public function getTotalSubscriptionsAttribute() {
		return $this->subscriptionCount();
	}

	public function getTotalVideoViewsAttribute() {
		return $this->getTotalVideoViewsCount();
	}

	public function getTotalVideoCountAttribute() {
		return $this->channelVideoCount();
	}

	public function getHasCurrentUserBoughtThisChannelAttribute() {
		if (Auth::user()) {
			return $this->hasCurrentUserPaid();
		}

		return false;
	}

	public function ownedByCurrentUser() {
		if (Auth::guest()) {
			return false;
		}

		return $this->user_id === Auth::user()->id;
	}

	public function subscriptionCount() {
		return $this->subscriptions->count();
	}

	public function getTotalVideoViewsCount() {
		$visible_videos = $this->videos()
		                       ->visible()
		                       ->pluck('id');

		return $this->hasManyThrough(VideoView::class, Video::class)
		            ->whereIn('video_id', $visible_videos)
		            ->count();
	}

	public function isVisible() {
		return $this->isPublic();
	}

	public function isPublic() {
		return $this->visibility === 'public';
	}

	public function isFree() {
		return $this->type === 'free';
	}

	public function scopePublic($query) {
		return $query->where('channel.visibility', 'public');
	}

	public function scopeFree($query) {
		return $query->where('channel.type', 'free');
	}

	public function scopeVisible($query) {
		return $query->public();
	}

	public function channelVideoCount() {
		return $this->videos()->where('processed', true)->count();
	}

	public function calculateCommission() {
		return (config('marketplace.sales.commission') / 100) * $this->price;
	}

	public function hasCurrentUserPaid() {
		if (Auth::user()) {
			return (bool) Auth::user()->bought()->where('item_id', $this->id)->count();
		}

		return false;
	}
}