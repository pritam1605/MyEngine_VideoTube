<?php

namespace App\Http\Controllers;

use Location;
use App\Models\Channel;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use App\Repositories\VideoRepository;

class HomeController extends Controller {

    public function index(Request $request) {
        $location = Location::get($request->ip());
        RequestLog::create([
            'access_url' => url(''),
            'ip_address' => $request->ip(),
            'country_code' => $location ? $location->countryCode : null,
            'region_code' => $location ? $location->regionCode : null,
            'region_name' => $location ? $location->regionName : null,
            'city' => $location->cityName ?: null,
        ]);

        return view('home');
    }

    public function loadHomepageVideos(Request $request, VideoRepository $user_repo) {
        if ($request->ajax()) {
            $videos = $user_repo->getUserVideosFromSubscriptions($request->user());

            return response()->json([
                'data' => [
                    'message' => 'Success',
                    'videos' => $videos,
                ]
            ], 200);
        }
    }
}