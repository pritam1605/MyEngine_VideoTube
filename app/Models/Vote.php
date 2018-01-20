<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {

	protected $table = 'vote';

	protected $fillable = [
		'user_id',
		'type',
	];

	public function votable() {
		return $this->morphTo();
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

}