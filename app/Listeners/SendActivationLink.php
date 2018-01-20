<?php

namespace App\Listeners;

use Mail;
use App\Mail\SendActivationToken;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendActivationLink {

    public function handle($event) {
        Mail::to($event->user)
            ->queue(new SendActivationToken($event->user->activationToken));
    }
}