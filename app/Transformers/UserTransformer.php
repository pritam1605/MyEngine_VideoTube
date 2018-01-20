<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

	public function transform(User $user = null) {

		return [
			'user_profile_imageUrl' =>  $user ? $user->getImageUrl() : config('myengine.cdn.cloudfront.images') . 'user/default.png',
		];
	}
}