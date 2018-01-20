<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest {

    public function authorize() {
        return Auth::guest();
    }

    public function rules() {
        return [
            'password' => 'required|min:8|dumbpwd|confirmed',
        ];
    }
}