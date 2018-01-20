<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Video;
use Illuminate\Http\Request;

class WebhookEncodingController extends Controller {

	public function handle(Request $request) {

		// Logging everything in the log file
		Log::info($request);

		$event = camel_case($request->event);

		if (method_exists($this, $event)) {
			$this->{$event}($request);
		}
	}

	protected function videoEncoded(Request $request) {
		// This is called when all encodings for a video have been completed.
		$video = $this->getVideoByName($request->original_filename);
		$video->processed = true;
		$video->online_video_id = $request->encoding_ids[0];
		$video->processed_percentage = 100;

		$video->save();
	}

	protected function encodingProgress(Request $request) {
		// This is called approximately every 10s while an encoding is processing, and returns the percentage complete.
		$video = $this->getVideoByName($request->original_filename);
		$video->processed_percentage = $request->progress;

		$video->save();
	}

	protected function videoCreated(Request $request) {
		// This is called once a video has downloaded (if a url was given), validated, and uploaded to your S3 bucket. The status will either be success or fail. An array of encoding ids is provided, although note that the encodings will not have been processed at this state.
	}

	protected function encodingCompleted(Request $request) {
		// This is called for each encoding when the encoding is done (or failed).
	}

	protected function getVideoByName($filename) {
		return Video::where('online_video_filename', $filename)->firstOrFail();
	}
}