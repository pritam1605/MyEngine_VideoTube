<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Subscription;
use Illuminate\Http\Request;

class ChannelSubscriptionController extends Controller {

	public function index(Request $request, Channel $channel) {

		$response = [
			'total_subscriptions' => $channel->subscriptionCount(),
			'can_current_user_subscribe' => $request->user() ? !$request->user()->ownsChannel($channel) : null,
			'is_current_user_subscribed' => $request->user() ? $request->user()->isUserSubscribedTo($channel) : null,
		];

		return response()->json([
			'data' => $response,
		], 200);

	}

	public function create(Request $request, Channel $channel) {
		$this->authorize('subscribe', $channel);

		$request->user()->subscriptions()->create([
			'channel_id' => $channel->id,
		]);

		return response()->json([
			'data' => [
				'message' => 'Success',
			]
		], 200);
	}

	public function delete(Request $request, Channel $channel) {
		$this->authorize('unsubscribe', $channel);

		$request->user()->subscriptions()->where('channel_id', $channel->id)->delete();

		return response()->json([
			'data' => [
				'message' => 'Success',
			]
		], 200);
	}
}
