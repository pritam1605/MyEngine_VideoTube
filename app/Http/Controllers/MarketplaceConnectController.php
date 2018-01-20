<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Http\Request;

class MarketplaceConnectController extends Controller {

	public function store(Request $request, Guzzle $guzzle) {

		if (!$request->get('code')) {
			return redirect()->back();
		}

		if ($request->get('state') !== csrf_token()) {
			return redirect()->back();
		}


		$stripeRequest = $guzzle->request('POST', 'https://connect.stripe.com/oauth/token', [
			'form_params' => [
				'client_secret' => config('services.stripe.secret'),
				'code' => $request->get('code'),
				'grant_type' => 'authorization_code',
			]
		]);

		$stripeResponse = json_decode($stripeRequest->getBody());

		$request->user()->update([
			'stripe_id' => $stripeResponse->stripe_user_id,
			'stripe_key' => $stripeResponse->stripe_publishable_key,
		]);

		return redirect()->back();
	}
}
