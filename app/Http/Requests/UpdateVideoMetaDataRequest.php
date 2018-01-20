<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoMetaDataRequest extends FormRequest {

    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        return [
            'title' => 'required|max:30',
            'description' => 'max:1000',
            'visibility' => 'required|in:public,unlisted,private',
            'type' => 'required|in:paid,free',
            'price' => 'required|numeric',
        ];
    }
}
