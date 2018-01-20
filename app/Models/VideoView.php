<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class VideoView extends Model {

	protected $table = 'video_view';

	protected $fillable = [
		'user_id',
		'ip_address',
	];

	public function scopeByUser($query, User $user) {
		return $query->where('user_id', $user->id);
	}

	public function scopeLatestViewByUser($query, User $user) {
		return $query->byUser($user)->latest();
	}

	public function scopeLatestViewByIpAddress($query, $ip_address) {
		return $query->where('ip_address', $ip_address)->latest();
	}
}
