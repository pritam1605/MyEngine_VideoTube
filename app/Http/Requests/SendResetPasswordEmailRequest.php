<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class SendResetPasswordEmailRequest extends FormRequest
{

    public function authorize() {
        return Auth::guest();
    }

    public function rules() {
        return [
            'email' => 'required|email|exists:user,email|is_not_social_account',
        ];
    }
}
