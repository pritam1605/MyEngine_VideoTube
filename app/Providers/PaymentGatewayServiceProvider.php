<?php

namespace App\Providers;

use Stripe\Stripe;
use Illuminate\Support\ServiceProvider;

class PaymentGatewayServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        Stripe::setApiKey(config('services.stripe.secret'));
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
