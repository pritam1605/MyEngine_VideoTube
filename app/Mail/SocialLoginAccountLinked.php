<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SocialLoginAccountLinked extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $service;
    public $subject;

    public function __construct(User $user, $service, $subject) {
        $this->user = $user;
        $this->service = $service;
        $this->subject = $subject;
    }

    public function build() {
        return $this->subject($this->subject)
                    ->markdown('emails.auth.sociallogin.service')
                    ->text('emails.auth.sociallogin.service_text');
    }
}