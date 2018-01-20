<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest {

    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        return [
            'currentPassword' => 'required|match_old_password',
            'password' => 'required|min:8|dumbpwd|confirmed',
        ];
    }
}