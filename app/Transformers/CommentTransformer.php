<?php

namespace App\Transformers;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'channel',
		'replies',
	];

	public function transform(Comment $comment) {

		return [
			'id' => (int) $comment->id,
			'user' => $comment->user,
			'user_id' => $comment->user_id,
			'body' => $comment->body,
			'parent_id' => $comment->parent_id,
			'created_at_human' => $comment->created_at->diffForHumans(),
		];
	}

	public function includeChannel(Comment $comment) {
		return $this->item($comment->user->channels->first(), new ChannelTransformer);
	}

	public function includeReplies(Comment $comment) {
		return $this->collection($comment->replies, new CommentTransformer);
	}
}