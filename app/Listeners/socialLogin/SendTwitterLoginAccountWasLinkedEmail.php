<?php

namespace App\Listeners\SocialLogin;

use Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SocialLogin\TwitterLoginAccountWasLinked;
use App\Mail\SocialLoginAccountLinked;

class SendTwitterLoginAccountWasLinkedEmail {

    public function handle(TwitterLoginAccountWasLinked $event) {
        Mail::to($event->user)
			->queue(new SocialLoginAccountLinked($event->user, 'twitter', 'Twitter account was linked'));
    }
}