<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfMarketPlaceConnected {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if (auth()->user()->stripe_id) {
            return redirect()->route('all.channels', auth()->user());
        }

        return $next($request);
    }
}
