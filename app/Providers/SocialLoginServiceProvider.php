<?php

namespace App\Providers;

use App\Models\UserSocialLogin;
use Illuminate\Support\ServiceProvider;
use App\Observers\UserSocialLoginObserver;

class SocialLoginServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        UserSocialLogin::observe(UserSocialLoginObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}