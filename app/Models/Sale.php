<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {

	protected $table = 'sale';

    protected $fillable = [
		'seller_id',
		'buyer_id',
		'item_id',
		'item_type',
		'sale_price',
		'sale_commission',
    ];
}
