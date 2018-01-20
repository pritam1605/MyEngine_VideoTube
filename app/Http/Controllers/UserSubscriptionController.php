<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller {

	public function index(User $user) {
		$this->authorize('canViewSubscriptions', $user);

		return view('users.subscriptions.index');
	}


	public function getUserSubscribedChannelList(User $user, Request $request) {
		$this->authorize('canViewSubscriptions', $user);

		if ($request->ajax()) {

			$channels = $user->subscribedChannels;

			return response()->json([
				'data' => [
					'message' => 'Success',
					'channels' => $channels,
				],
			], 200);
		}
	}
}
