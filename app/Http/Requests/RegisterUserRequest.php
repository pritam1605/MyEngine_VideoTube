<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest {

    public function authorize() {
        return Auth::guest();
    }

    public function rules() {
        return [
            'username' => 'required|max:20|alpha_dash|unique:user',
            'firstName' => 'required|min:2|max:15|alpha',
            'lastName' => 'required|min:2|max:15|alpha',
            'email' => 'required|email|max:30|unique:user',
            'password' => 'required|min:8|dumbpwd|confirmed',
            'channelName' => 'required|max:15|alpha_dash|unique:channel,name',
        ];
    }
}