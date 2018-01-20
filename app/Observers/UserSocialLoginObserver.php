<?php

namespace App\Observers;

use App\Models\UserSocialLogin;

class UserSocialLoginObserver {

	public function created(UserSocialLogin $user_social_login) {

		$this->handleRegisteredEvent('created', $user_social_login);
	}

	protected function handleRegisteredEvent($event, UserSocialLogin $user_social_login) {
		$event_class = config("sociallogin.services.{$user_social_login->login_service}.events.{$event}");

		if ($event_class === null) {
			return;
		}
		event(new $event_class($user_social_login->user()->first()));
	}
}