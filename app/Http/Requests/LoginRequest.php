<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {

    public function authorize() {
        return Auth::guest();
    }

    public function rules() {
        return [
            'usernameOrEmail' => 'required',
            'password' => 'required',
        ];
    }
}
