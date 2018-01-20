<?php

namespace App\Mail;

use App\Models\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetToken extends Mailable {
    use Queueable, SerializesModels;

    public $token;

    public function __construct(PasswordReset $token) {
        $this->token = $token;
    }

    public function build() {
        return $this->subject('Reset your MyEngine password')
                    ->markdown('emails.password.reset')
                    ->text('emails.password.reset_text');
    }
}