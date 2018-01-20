<?php

namespace App\Http\Controllers;

use Cache;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Repositories\VideoRepository;
use App\Http\Requests\UpdateVideoMetaDataRequest;
use App\Http\Requests\CreateVideoMetaDataRequest;

class VideoController extends Controller {

	public function index(User $user) {
		$this->authorize('canViewAllUserVideos', $user);

		return view('videos.index');
	}

	public function allUserVideos(Request $request, User $user) {

		$this->authorize('canViewAllUserVideos', $user);

		if ($request->ajax()) {
			return $this->sendAjaxResponse($request);
		}
	}

	public function store(CreateVideoMetaDataRequest $request) {
		$uid = uniqid(true);

		$video = new Video();
		$video->uid = $uid;
		$video->title = $request->get('title');
		$video->description = $request->get('description');
		$video->visibility = $request->get('visibility');
		$video->type = $request->get('type');
		$video->online_video_filename = "{$uid}.{$request->get('extension')}";
		$video->channel_id = null;

		$video->save();

		return response()->json([
			'data' => [
				'uid' => $uid,
			]
		], 200);
	}

	public function update(UpdateVideoMetaDataRequest $request, Video $video) {
		$this->authorize('update', $video);

		$video->update([
			'title' => $request->get('title'),
			'description' => $request->get('description'),
			'visibility' => $request->get('visibility'),
			'allows_vote' => $request->get('allows_vote'),
			'allows_comment' => $request->get('allows_comment'),
			'channel_id' => $request->get('channel_id'),
			'type' => $request->get('type'),
			'price' => $request->get('price'),
		]);

		// Deleting the cache if the user updates any video metadata
		// Cache::forget("user_videos_{$request->user()->username}");

		if ($request->ajax()) {
			return $this->sendAjaxResponse($request);
		}

		return redirect()->back();
	}

	public function delete(Request $request, Video $video) {
		$this->authorize('delete', $video);

		// Deleting the cache if the user deletes any video
		// Cache::forget("user_videos_{$request->user()->username}");

		$video->delete();

		if ($request->ajax()) {
			return $this->sendAjaxResponse($request);
		}

		return redirect()->back();
	}

	public function show() {
		return view('videos.video');
	}

	public function showVideo(Request $request, Video $video) {
		if ($request->ajax()) {
			return response()->json([
				'data' => [
					'message' => 'Success',
					'video' => $video->load('channel'),				// lazy loading channel details along with the video
					'other_videos' => $this->getPlaylistVideos($video)
				],
			], 200);
		}
	}

	protected function sendAjaxResponse(Request $request) {
		if ($request->get('page')) {
			// to display results of the search box
			$videos = Video::search($request->get('page'))->get();
		} else {
			$video_repo = new VideoRepository();
			$videos = $video_repo->getUserVideos($request->user());
		}

		return response()->json([
			'data' => [
				'message' => 'Success',
				'videos' => $videos,
			],
		], 200);
	}

	protected function getPlaylistVideos(Video $video) {
		$video_repo = new VideoRepository();
		return $video_repo->getVideosForSidePane($video);
	}
}