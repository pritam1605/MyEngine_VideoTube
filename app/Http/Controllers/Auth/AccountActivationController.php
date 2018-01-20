<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivationToken;
use App\Http\Controllers\Controller;
use App\Events\UserRequestedAccountActivationLinkResend;

class AccountActivationController extends Controller {

	public function activateAccount(ActivationToken $token, Request $request) {

	    if (($user = User::byEmail($request->get('email'))->first()) && ($token->user->email === $request->get('email'))) {

			$token->user()->update([
				'active' => true,
			]);

			$token->delete();
			Auth::login($user, false);

			return redirect()->route('home');
		}

		return redirect()->route('home');
	}

	public function resendActivationLink(Request $request) {

        if ($user = User::byEmail($request->get('email'))->first()) {
            if (!$user->isActive() && $user->activationToken) {

                // fire the event that will resend the activation email
                event(new UserRequestedAccountActivationLinkResend($user));
            }
        }

        return redirect()->back();
    }
}