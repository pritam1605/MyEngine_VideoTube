<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {

	use SoftDeletes;

	protected $table = 'comment';

	protected $fillable = [
		'user_id',
		'parent_id',
		'body',
	];

	public function commentable() {
		return $this->morphTo();
	}

	public function replies() {
		return $this->hasMany(Comment::class, 'parent_id', 'id');
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}
}