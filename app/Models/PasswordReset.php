<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\UserToken;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model {

	// using trait to perform common function between passwordless entry and password reset
	use UserToken;

	protected $table = 'password_reset';

	protected $fillable = [
		'token',
	];

	const TOKEN_EXPIRY = 300;
}
