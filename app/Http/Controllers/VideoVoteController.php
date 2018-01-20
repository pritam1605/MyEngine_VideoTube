<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoVoteController extends Controller {

	public function index(Request $request, Video $video) {

		return response()->json([
			'data' => [
				'message' => 'Success',
				'upVotes' => $video->getUpVotes()->count(),
				'downVotes' => $video->getDownVotes()->count(),
				'userVote' => $video->getUserVoteType($request->user()),
			]
		], 200);
	}

	public function store(Request $request, Video $video) {

		$this->authorize('vote', $video);

		if ($vote = $this->currentUserHasSimilarVote($request, $video)) {
			$vote->delete();
		} else {

			if ($vote = $this->currentUserHasAlreadyVoted($request, $video)) {
				$vote->delete();
			}

			$video->votes()->create([
				'type' => $request->get('type'),
				'user_id' => $request->user()->id,
			]);
		}

		return response()->json([
			'data' => [
				'message' => 'Success',
				'upVotes' => $video->getUpVotes()->count(),
				'downVotes' => $video->getDownVotes()->count(),
				'userVote' => $video->getUserVoteType($request->user()),
			]
		], 200);
	}

	protected function currentUserHasAlreadyVoted(Request $request, Video $video) {

		return $video->votes()->where('user_id', $request->user()->id)->first();
	}

	protected function currentUserHasSimilarVote(Request $request, Video $video) {
		return $video->votes()->where([
			['user_id', $request->user()->id],
			['type', $request->get('type')],
		])->first();
	}
}
