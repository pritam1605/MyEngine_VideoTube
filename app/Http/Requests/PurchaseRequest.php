<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest {
    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        return [
            'stripeEmail' => 'required|email',
            'stripeToken' => 'required',
        ];
    }
}
