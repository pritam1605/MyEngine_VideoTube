<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        'App\Events\UserRegistered' => [
            'App\Listeners\SendActivationLink',
        ],

        'App\Events\UserRequestedAccountActivationLinkResend' => [
            'App\Listeners\SendActivationLink',
        ],

        'App\Events\UserForgotPassword' => [
            'App\Listeners\SendPasswordResetLink',
        ],

        'App\Events\UserRequestedPasswordlessEntry' => [
            'App\Listeners\SendPassowrdlessEntryLink',
        ],

        'App\Events\SocialLogin\GithubLoginAccountWasLinked' => [
            'App\Listeners\SocialLogin\SendGithubLoginAccountWasLinkedEmail',
        ],

        'App\Events\SocialLogin\TwitterLoginAccountWasLinked' => [
            'App\Listeners\SocialLogin\SendTwitterLoginAccountWasLinkedEmail',
        ],

        'App\Events\SocialLogin\GoogleLoginAccountWasLinked' => [
            'App\Listeners\SocialLogin\SendGoogleLoginAccountWasLinkedEmail',
        ],

        'App\Events\SocialLogin\FacebookLoginAccountWasLinked' => [
            'App\Listeners\SocialLogin\SendFacebookLoginAccountWasLinkedEmail',
        ],

        'App\Events\ChannelPurchased' => [
            'App\Listeners\SendEmailToPurchaser',
        ],

        'App\Events\ChannelSold' => [
            'App\Listeners\SendEmailToSeller',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
