<?php

namespace App\Listeners;

use Mail;
use App\Events\UserForgotPassword;
use App\Mail\SendPasswordResetToken;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetLink {

    public function handle(UserForgotPassword $event) {
        Mail::to($event->user)
            ->queue(new SendPasswordResetToken($event->user->passwordResetToken));
    }
}