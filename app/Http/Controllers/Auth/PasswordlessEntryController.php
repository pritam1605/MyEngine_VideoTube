<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordlessEntry;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendMagicLinkRequest;
use App\Events\UserRequestedPasswordlessEntry;

class PasswordlessEntryController extends Controller {

	public function index() {
		return view('auth.passwordless');
	}

	public function sendMagicLink(SendMagicLinkRequest $request) {

		$user = User::byEmail($request->get('email'))->first();

        if (!PasswordlessEntry::hasPendingToken($user)) {
            $user->passwordlessEntryToken()->create([
                'token' => str_random(128),
            ]);
        }

        // fire the event to send the email
        event(new UserRequestedPasswordlessEntry($user, $request->get('rememberMe')));

        return response()->json([
        	'data' => [
        		'status' => 'success',
        		'message' => 'Magic link has been emailed to you. Note: It is only valid for 5 minutes',
        	]
        ], 200);
	}

	public function logInMagically(PasswordlessEntry $token, Request $request) {

	    if (($user = User::byEmail($request->get('email'))->first()) && ($token->user->email === $request->get('email')) && $token->user->isActive()) {

	    	if (!$token->isExpired()) {
				$token->delete();
				Auth::login($user, $request->get('remember_me', false));
				return redirect()->route('home');
	    	}

	    	$token->delete();
			return redirect()->route('passwordless.entry.page');
		}

		return redirect()->route('login');
	}
}