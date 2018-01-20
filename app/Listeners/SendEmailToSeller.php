<?php

namespace App\Listeners;

use Mail;
use App\Events\ChannelSold;
use App\Mail\SendSaleNotificationEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailToSeller {

    public function handle(ChannelSold $event) {
        Mail::to($event->seller)
            ->queue(new SendSaleNotificationEmail($event->seller, $event->channel));
    }
}
