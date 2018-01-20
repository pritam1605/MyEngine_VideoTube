<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class EditUserProfileRequest extends FormRequest {

    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        $channel_id = Auth::user()->channels->first()->id;

        return [
            'firstName' => 'required|min:2|max:15|alpha',
            'lastName' => 'required|min:2|max:15|alpha',
            'profileImage' => 'nullable|image|max:4096',
        ];
    }

    public function messages() {
        return [
            'profileImage.image' => 'Only jpeg, jpg, png, bmp, gif and svg formats are supported.',
        ];
    }
}