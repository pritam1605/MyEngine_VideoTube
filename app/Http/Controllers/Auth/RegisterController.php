<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;

class RegisterController extends Controller {

	public function showRegisterForm() {
		return view('auth.register');
	}

	public function register(RegisterUserRequest $request) {
		$user = new User();
    	$user->username = $request->get('username');
    	$user->email = $request->get('email');
        $user->first_name = $request->get('firstName');
        $user->last_name = $request->get('lastName');
    	$user->password = bcrypt($request->get('password'));

    	if ($user->save()) {

    		$user->channels()->create([
                'name' => $request->get('channelName'),
                'slug' => uniqid(true),
    			'is_default' => true,
    		]);

            // send activation email
            $user->activationToken()->create([
                'token' => str_random(128),
            ]);

            event(new UserRegistered($user));

    		return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Successfully registered. Please activate your account',
                ],
            ], 200);
    	}

        return response()->json([
            'data' => [
                'status' => 'failed',
                'message'  => 'Account registeration failed',
            ]
        ], 200);
	}
}
