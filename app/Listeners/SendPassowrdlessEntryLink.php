<?php

namespace App\Listeners;

use Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SendPasswordlessEntryToken;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserRequestedPasswordlessEntry;

class SendPassowrdlessEntryLink {

    public function handle(UserRequestedPasswordlessEntry $event) {
        Mail::to($event->user)
            ->queue(new SendPasswordlessEntryToken($event->user->passwordlessEntryToken, $event->remember_me));
    }
}