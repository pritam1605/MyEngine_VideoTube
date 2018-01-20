<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialLoginController extends Controller {

	public function redirectToProvider($service, Request $request) {
		return Socialite::driver($service)->redirect();
	}

	public function handleProviderCallback($service, Request $request) {

		// If user cancels the Social Auth process
		if (($service === 'twitter' && !$request->input('oauth_token'))) {
			return redirect('login');
		} else if ($service !== 'twitter' && !$request->input('code')) {
			return redirect('login');
		}

		$service_user = Socialite::driver($service)->user();

		$user = $this->getExistingUser($service_user, $service);

		if (!$user) {
			$user = User::create([
					'username' => $this->getNameorUsername($service_user),
					'email' => $service_user->getEmail(),
					'password' => 'social_login',
					'active' => true,
				]);

			$user->channels()->create([
    			'name' => 'My Channel',
    			'slug' => uniqid(true),
    		]);
		}

		if ($this->needsToCreateSocialLogin($user, $service)) {
			$user->socialLogin()->create([
				'social_login_id' => $service_user->getId(),
				'login_service' => $service,
			]);
		}

		Auth::login($user, false);

		return redirect()->route('home');
	}

	protected function needsToCreateSocialLogin(User $user, $service) {
		return !$user->hasSocialLoginLinked($service);
	}

	protected function getExistingUser($service_user, $service) {
		return User::where('email', $service_user->getEmail())->orWhereHas('socialLogin', function ($q) use ($service_user, $service) {
			$q->where('social_login_id', $service_user->getId())->where('login_service', $service);
		})->first();
	}

	protected function getNameOrUsername($service_user) {
		return $service_user->getNickname() ?:  $service_user->getName();
	}
}