<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use App\Transformers\CommentTransformer;
use App\Http\Requests\PostCommentRequest;

class VideoCommentController extends Controller {

	public function index(Request $request, Video $video) {

		$comments = $video->comments()->latest()->get();
		$current_user = $request->user();

		return response()->json([
			'comments' => fractal()->collection($comments)
						           ->parseIncludes(['channel', 'replies', 'replies.channel',])
						           ->transformWith(new CommentTransformer)
						           ->toArray(),

            'current_user' => fractal()->item($current_user)
						               ->transformWith(new UserTransformer)
						               ->toArray()
		]);
	}

	public function store(PostCommentRequest $request, Video $video) {

		$this->authorize('comment', $video);

		$comment = $video->comments()->create([
			'body' => $request->get('body'),
			'user_id' => $request->user()->id,
			'parent_id' => $request->get('parent_id', null),
		]);

		return response()->json(
			fractal()->item($comment)
			         ->parseIncludes(['channel', 'replies', 'replies.channel',])
			         ->transformWith(new CommentTransformer)
			         ->toArray()
		);
	}

	public function delete(Video $video, Comment $comment) {

		$this->authorize('delete', $comment);

		$comment->delete();

		$comments = $video->comments()->latest()->get();

		return response()->json([
			'comments' => fractal()->collection($comments)
						           ->parseIncludes(['channel', 'replies', 'replies.channel',])
						           ->transformWith(new CommentTransformer)
						           ->toArray()
		], 200);

		return response()->json([
			'data' => [
				'message' => 'Success',
			],
		], 200);
	}
}