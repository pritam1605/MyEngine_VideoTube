<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPurchaseNotificationEmail extends Mailable {

    public $buyer;
    public $channel;

    public function __construct(User $buyer, Channel $channel) {
        $this->buyer = $buyer;
        $this->channel = $channel;
    }

    public function build() {
        return $this->subject('MyEngine Order Confirmation')
                    ->markdown('emails.marketplace.purchase.notification')
                    ->text('emails.marketplace.purchase.notification_text');
    }
}