<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivationToken extends Model {

	protected $table = 'activation_token';

	protected $fillable = [
		'token',
	];

	// Route Model Binding
	public function getRouteKeyName() {
		return 'token';
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}
}