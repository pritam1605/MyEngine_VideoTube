<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Channel;
use Illuminate\Http\Request;
use App\Jobs\ImageUploadJob;
use App\Repositories\ChannelRepository;
use App\Http\Requests\EditChannelRequest;

class ChannelSettingController extends Controller {

	public function editChannel(Request $request, Channel $channel) {

		// using the policy that the user can only see his own channel page
		$this->authorize('edit', $channel);

		if ($request->ajax()) {
			return response()->json([
				'data' => [
					'message' => 'Success',
					'channel' => $channel,
				],
			], 200);
		}
	}

	public function update(EditChannelRequest $request, Channel $channel) {

		// using the policy that the user can only update his own channel page
		$this->authorize('update', $channel);

		$channel->update([
			'name' => $request->get('name'),
			'slug' => $request->get('slug'),
			'description' => $request->get('description'),
			'visibility' => $request->get('visibility'),
			'type' => $request->get('type'),
			'price' => $request->get('type') === 'free' ? 0 : $request->get('price'),
		]);

		// Updating the visibility, type and price of all videos in the channel
		$channel->videos()->update([
			'visibility' => $channel->visibility,
			'type' => $channel->type,
			'price' => $channel->price,
		]);

		if ($request->file('channelImage')) {
			$request->file('channelImage')->move(storage_path() . '/uploads/', $file_id = uniqid(true));

			//Queueing the job
			$this->dispatch(new ImageUploadJob($channel, $file_id, 'channel'));
		}

		if ($request->ajax()) {

			$channel_repo = new ChannelRepository();
			$channels = $channel_repo->getUserChannels($request->user());

			return response()->json([
				'data' => [
					'message' => 'Success',
					'channel' => $channels,
				]
			], 200);
		}

		return redirect()->back();
	}

	public function delete(Request $request, Channel $channel) {
		$channel->delete();

		if ($request->ajax()) {

			$channel_repo = new ChannelRepository();
			$channels = $channel_repo->getUserChannels($request->user());

			return response()->json([
				'data' => [
					'message' => 'Success',
					'channel' => $channels,
				]
			], 200);
		}

		return redirect()->back();
	}
}