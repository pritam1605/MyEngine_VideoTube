<?php

namespace App\Listeners\SocialLogin;

use Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SocialLogin\FacebookLoginAccountWasLinked;
use App\Mail\SocialLoginAccountLinked;

class SendFacebookLoginAccountWasLinkedEmail {

    public function handle(FacebookLoginAccountWasLinked $event) {
        Mail::to($event->user)
            ->queue(new SocialLoginAccountLinked($event->user, 'facebook', 'Facebook account was linked'));
    }
}
