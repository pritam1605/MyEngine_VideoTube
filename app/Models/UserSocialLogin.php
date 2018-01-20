<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialLogin extends Model {

	protected $table = 'user_social_login';

	protected $fillable = [
		'social_login_id',
		'login_service',
	];

	public function user() {
		return $this->belongsTo(User::class);
	}
}