<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;

trait UserToken {

	public function getRouteKeyName() {
		return 'token';
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public static function hasPendingToken(User $user) {
        // Only one token per user is allowed in the database at any given time
        return (bool) static::where('user_id', $user->id)->first();
    }

    public function isExpired() {
    	return $this->created_at->diffInSeconds(Carbon::now()) > static::TOKEN_EXPIRY;
    }

    public function scopeExpired($query) {
    	return $query->where('created_at', '<=', Carbon::now()->subSeconds(static::TOKEN_EXPIRY));
    }
}