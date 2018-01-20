<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Channel;
use App\Jobs\ImageUploadJob;
use Illuminate\Http\Request;
use App\Repositories\VideoRepository;
use App\Repositories\ChannelRepository;
use App\Http\Requests\CreateChannelRequest;

class ChannelController extends Controller {

	public function index(Channel $channel) {

		return view('channels.index')->with([
			'channel_slug' => $channel->slug,
		]);
	}

	public function getChannelVideos(Request $request, VideoRepository $video_repo, Channel $channel) {

		if ($request->ajax()) {
			$videos = $video_repo->getChannelVideos($channel);

			return response()->json([
				'data' => [
					'message'=> 'Success',
					'videos' => $videos,
					'channel' => $channel,
				]
			], 200);
		}
	}

	public  function showCreateChannelPage() {
		return view('channels.create');
	}

	public function store(CreateChannelRequest $request) {

		$channel = new Channel();
		$channel->user_id = Auth::user()->id;
		$channel->name = $request->get('name');
		$channel->slug = $request->get('slug');
		$channel->description = $request->get('description');
		$channel->visibility = $request->get('visibility');
		$channel->type = $request->get('type');
		$channel->price = $channel->type === 'free' ? 0 : $request->get('price');
		$channel->save();

		if ($request->file('channelImage')) {
			$request->file('channelImage')->move(storage_path() . '/uploads/', $file_id = uniqid(true));

			//Queueing the job
			$this->dispatch(new ImageUploadJob($channel, $file_id, 'channel'));
		}

		if ($request->ajax()) {
			return response()->json([
				'data' => [
					'message' => 'Success',
					'channel' => $channel,
				]
			], 200);
		}

		return redirect()->route('edit.channel.settings', $channel);
	}

	public function showUserChannelsPage(User $user) {
		$this->authorize('canViewAllUserChannels', $user);

		return view('channels.list');
	}

	public function getAllUserChannels(Request $request, User $user) {
		$this->authorize('canViewAllUserChannels', $user);

		if ($request->ajax()) {
			$channel_repo = new ChannelRepository();
			$channels = $channel_repo->getUserChannels($request->user());

			return response()->json([
				'data' => [
					'message' => 'Success',
					'channels' => $channels,
				],
			], 200);
		}
	}

	public function getUserInfo(Request $request, Channel $channel) {

		if ($request->ajax()) {
			return response()->json([
				'data' => [
					'message' => 'Success',
					'channelOwner' => $channel->user,
				]
			], 200);
		}
	}
}