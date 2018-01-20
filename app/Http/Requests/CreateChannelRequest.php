<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateChannelRequest extends FormRequest {
    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        return [
            'name' => 'required|max:30|unique:channel,name',
            'slug' => 'required|max:30|alpha_num|unique:channel,slug',
            'description' => 'nullable|max:1000',
            'visibility' => 'required|in:public,unlisted,private',
            'type' => 'required|in:free,paid',
            'channelImage' => 'nullable|image|max:4096',
            'price' => 'required|numeric',
        ];
    }

    public function messages() {
        return [
            'slug.unique' => 'That unique URL has already been taken.',
            'channelImage.image' => 'Only jpeg, jpg, png, bmp, gif and svg formats are supported.',
        ];
    }
}