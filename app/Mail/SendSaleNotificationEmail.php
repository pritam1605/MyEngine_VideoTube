<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSaleNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $channel;

    public function __construct(User $seller, Channel $channel) {
        $this->seller = $seller;
        $this->channel = $channel;
    }

    public function build() {
        return $this->subject('MyEngine Sale Confirmation')
                    ->markdown('emails.marketplace.sale.notification')
                    ->text('emails.marketplace.sale.notification_text');
    }
}
