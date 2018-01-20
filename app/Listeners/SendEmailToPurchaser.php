<?php

namespace App\Listeners;

use Mail;
use App\Events\ChannelPurchased;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SendPurchaseNotificationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailToPurchaser {

    public function handle(ChannelPurchased $event) {
        Mail::to($event->buyer)
            ->queue(new SendPurchaseNotificationEmail($event->buyer, $event->channel));
    }
}