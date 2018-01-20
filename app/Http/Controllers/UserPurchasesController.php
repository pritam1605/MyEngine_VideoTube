<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Http\Request;

class UserPurchasesController extends Controller {

	public function index(User $user) {
		$this->authorize('canViewPurchases', $user);

		return view('users.purchases.index');
	}


	public function getUserPurchasedChannelList(User $user, Request $request) {
		$this->authorize('canViewPurchases', $user);

		if ($request->ajax()) {
			$channel_ids = $user->bought()->where('item_type', 'Channel')->pluck('item_id');

			$channels = Channel::find($channel_ids);

			return response()->json([
				'data' => [
					'message' => 'Success',
					'channels' => $channels,
				],
			], 200);
		}
	}
}
