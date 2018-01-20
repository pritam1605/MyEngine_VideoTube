<?php

namespace App\Listeners\SocialLogin;

use Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SocialLogin\GoogleLoginAccountWasLinked;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\SocialLoginAccountLinked;

class SendGoogleLoginAccountWasLinkedEmail {

    public function handle(GoogleLoginAccountWasLinked $event) {
        Mail::to($event->user)
			->queue(new SocialLoginAccountLinked($event->user, 'google', 'Google account was linked'));
    }
}