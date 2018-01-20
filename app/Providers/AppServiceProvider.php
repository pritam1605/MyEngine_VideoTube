<?php

namespace App\Providers;

use Auth;
use Hash;
use Validator;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Validator::extend('match_old_password', function ($attribute, $value, $parameters, $validator) {
            if (Hash::check($value, Auth::user()->password)) {
                return true;
            }

            return false;
        });

        Validator::extend('is_not_social_account', function ($attribute, $value, $parameters, $validator) {
            $user = User::byEmail($value)->first();

            if ($user && $user->password !== 'social_login') {
                return true;
            }

            return false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
