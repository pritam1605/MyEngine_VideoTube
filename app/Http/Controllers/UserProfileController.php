<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\ImageUploadJob;
use App\Repositories\ChannelRepository;
use App\Http\Requests\EditUserProfileRequest;

class UserProfileController extends Controller {

	public function index(User $user) {

		// using the policy that the user can only see his own profile page
		$this->authorize('canViewUserProfile', $user);

		return view('users.edit');
	}

	public function getUserProfile(Request $request, User $user) {

		// using the policy that the user can only see his own profile page
		$this->authorize('canViewUserProfile', $user);

		if ($request->ajax()) {
			return response()->json([
				'data' => [
					'message' => 'Success',
					'user' => $user,
				],
			], 200);
		}
	}

	public function update(EditUserProfileRequest $request, user $user) {

		// using the policy that the user can only update his own profile
		$this->authorize('canEditUserProfile', $user);

		$user->update([
			'first_name' => $request->get('firstName'),
			'last_name' => $request->get('lastName'),
		]);

		if ($request->file('profileImage')) {
			$request->file('profileImage')->move(storage_path() . '/uploads/', $file_id = uniqid(true));

			//Queueing the job
			$this->dispatch(new ImageUploadJob($user, $file_id, 'user'));
		}

		if ($request->ajax()) {
			return response()->json([
				'data' => [
					'message' => 'Success',
					'user' => $user,
				]
			], 200);
		}

		return redirect()->route('user.profile', $user);
	}

	public function showDashboard(Request $request, User $user) {
		return view('users.index')->with([
			'user' => $user,
		]);
	}

	public function getDashboardData(Request $request, ChannelRepository $channel_repo, User $user) {

		if ($request->ajax()) {
			$channels = $channel_repo->getUserChannels($user);

			return response()->json([
				'data' => [
					'message'=> 'Success',
					'channels' => $channels,
				]
			], 200);
		}
	}

	public function getUserInfo(Request $request, User $user) {

		if ($request->ajax()) {

			return response()->json([
				'data' => [
					'message'=> 'Success',
					'user' => $user,
				]
			], 200);
		}
	}
}
