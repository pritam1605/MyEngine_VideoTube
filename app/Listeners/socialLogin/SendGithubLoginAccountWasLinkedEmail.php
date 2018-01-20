<?php

namespace App\Listeners\SocialLogin;

use Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SocialLogin\GithubLoginAccountWasLinked;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\SocialLoginAccountLinked;

class SendGithubLoginAccountWasLinkedEmail {

    public function handle(GithubLoginAccountWasLinked $event) {
        Mail::to($event->user)
			->queue(new SocialLoginAccountLinked($event->user, 'github', 'Github account was linked'));
    }
}