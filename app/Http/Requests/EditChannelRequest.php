<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class EditChannelRequest extends FormRequest {

    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {

        return [
            'name' => 'required|max:15|unique:channel,name,' . $this->channel->id,
            'slug' => 'required|max:30|alpha_num|unique:channel,slug,' . $this->channel->id,
            'visibility' => 'required|in:public,unlisted,private',
            'description' => 'nullable|max:1000',
            'channelImage' => 'nullable|image|max:4096',
            'price' => 'required|numeric',
            'type' => 'required|in:free,paid',
        ];
    }

    public function messages() {
        return [
            'slug.unique' => 'That unique URL has already been taken.',
            'channelImage.image' => 'Only jpeg, jpg, png, bmp, gif and svg formats are supported.',
        ];
    }
}