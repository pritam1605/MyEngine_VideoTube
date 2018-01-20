<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Channel;
use Illuminate\Http\Request;

class SearchController extends Controller {

	public function index() {

		return view('search.index');
	}

	public function getSearchList(Request $request) {

		$videos = Video::search($request->q)->where('visible', true)->get();
		$videos->load('channel');

		$channels = Channel::search($request->q)->where('visible', true)->get();

		if ($request->ajax()) {
			return response()->json([
				'data' => [
					'message' => 'Success',
					'videos' => $videos,
					'channels' => $channels,
				],
			], 200);
		}
	}
}