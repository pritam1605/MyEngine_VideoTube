<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller {

	public function showLoginForm() {
		return view('auth.login');
	}

	public function login(LoginRequest $request) {

	    $username_or_email = $request->get('usernameOrEmail');
	    $field_name = filter_var($username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

	    if ($user = User::where($field_name, $username_or_email)->first()){
	        if (!$user->isActive()) {
	            return response()->json([
	                'data' => [
	                	'status' => 'failed',
	                	'message' => 'Please activate your account. ' . '<strong><a href=' . route('resend.activate.account') .'?email=' . urlencode($user->email) . ' style="text-decoration: none;">Resend &rarr;</a></strong>',
	                ]
	            ], 200);
	        }
	    }

	    if (!Auth::attempt([$field_name => $username_or_email, 'password' => $request->get('password')], $request->input('rememberMe'))) {
			return response()->json([
				'data' => [
					'status' => 'failed',
					'message' => 'Username/Email and Password do not match.',
				]
			], 200);
		}

		return response()->json([
			'data' => [
				'status' => 'success',
				'message' => 'Successfully logged-in',
			],
		], 200);
	}

	public function logout() {
		Auth::logout();

		return redirect()->route('home');
	}
}
