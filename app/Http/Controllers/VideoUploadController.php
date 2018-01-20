<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Jobs\VideoUploadJob;
use Illuminate\Http\Request;
use App\Http\Requests\UploadVideoRequest;

class VideoUploadController extends Controller {

	public function index() {
		return view('videos.upload');
	}

	public function store(Request $request) {
		$video = Video::where('uid', $request->get('uid'))->firstOrFail();
		// $video = $channel->videos()->where('uid', $request->get('uid'))->firstOrFail();

		$request->file('video')->move(storage_path('uploads'), $video->online_video_filename);

		//Queueing the job
		$this->dispatch(new VideoUploadJob($video->online_video_filename));

		return response()->json([
			'data' => [
				'message' => 'Success',
			],
		], 200);
	}
}
