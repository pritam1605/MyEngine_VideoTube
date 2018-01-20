<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ActivationToken;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendActivationToken extends Mailable {
    use Queueable, SerializesModels;

    public $token;

    public function __construct(ActivationToken $token) {
        $this->token = $token;
    }

    public function build() {
        return $this->subject('Activate your MyEngine account')
                    ->markdown('emails.auth.activateAccount')
                    ->text('emails.auth.activateAccount_text');
    }
}