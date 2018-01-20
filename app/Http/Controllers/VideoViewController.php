<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoViewController extends Controller {

	const BUFFER = 60;			// Same user can only log video views after 60 seconds

	public function store(Request $request, Video $video) {
		// Can the current use log the view
		// eg. if the video is private, views can not be logged by other users
		if (!$video->canBeAccessedByCurrentUser()){
			return;
		}

		if (!$this->canBeLoggedNowByCurrentUser($request, $video)) {
			return;
		}

		$video->views()->create([
			'user_id' => $request->user() ? $request->user()->id : null,
			'ip_address' => $request->ip(),
		]);

		return response()->json([
			'data' => [
				'message' => 'Success',
			]
		], 200);
	}

	public function canBeLoggedNowByCurrentUser(Request $request, Video $video) {

		// if the user is logged in we will check by user_id
		if ($request->user()) {
			$last_user_view = $video->views()->latestViewByUser($request->user())->first();

			if ($this->isWithinBuffer($last_user_view)) {
				return false;
			}
		}

		// else we will check using the IP address
		$last_user_view = $video->views()->latestViewByIpAddress($request->ip())->first();

		if ($this->isWithinBuffer($last_user_view)) {
			return false;
		}

		return true;
	}

	protected function isWithinBuffer($last_user_view) {
		return $last_user_view && $last_user_view->created_at->diffInSeconds(Carbon::now()) < self::BUFFER;
	}
}