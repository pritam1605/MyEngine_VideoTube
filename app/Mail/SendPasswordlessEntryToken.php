<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PasswordlessEntry;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordlessEntryToken extends Mailable {
    use Queueable, SerializesModels;

    public $token;
    public $remember_me;

    public function __construct(PasswordlessEntry $token, $remember_me = false) {
        $this->token = $token;
        $this->remember_me = $remember_me;
    }

    public function build() {
        return $this->subject('Magically enter into your MyEngine account without password')
                    ->markdown('emails.auth.passwordlessEntry')
                    ->text('emails.auth.passwordlessEntry_text');
    }
}