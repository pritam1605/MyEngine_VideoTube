<?php

namespace App\Http\Controllers;

use Auth;
use Stripe\Charge;
use App\Models\User;
use App\Models\Sale;
use Stripe\Customer;
use App\Models\Channel;
use App\Events\ChannelSold;
use Illuminate\Http\Request;
use App\Events\ChannelPurchased;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller {

	public function purchaseChannel(PurchaseRequest $request, Channel $channel) {
		try {

			// Charge the Customer instead of the card:
			$charge = Charge::create([
				'amount' => $channel->price * 100,
				'currency' => 'usd',
				'source' => $request->get('stripeToken'),
				'application_fee' => $channel->calculateCommission() * 100,
				'description' => $channel->description,
  				'statement_descriptor' => 'MyEngine purchase',
				], [
				'stripe_account' => $channel->user->stripe_id,							// who is getting the money
			]);

			$sale = Sale::create([
				'seller_id' => $channel->user->id,
				'buyer_id' => Auth::user()->id,
				'item_id' => $channel->id,
				'item_type' => 'Channel',
				'sale_price' => number_format($channel->price, 2),
				'sale_commission' => number_format($channel->calculateCommission(), 2),
			]);

			// Send email to the buyer
			event(new ChannelPurchased(Auth::user(), $channel));

			// Send email to the seller
			event(new ChannelSold($channel->user, $channel));

		} catch (Exception $e) {
			if ($request->ajax()) {
                return response()->json([
					'data' => [
						'message' => 'Failed',
						'status' => $e->getMessage(),
					],
				], 422);
            }
		}

	}
}
