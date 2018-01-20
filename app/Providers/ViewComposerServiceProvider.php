<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\NavigationViewComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot() {
        View::composer('templates.partials._navigation', NavigationViewComposer::class);
    }

    public function register()
    {
        //
    }
}
