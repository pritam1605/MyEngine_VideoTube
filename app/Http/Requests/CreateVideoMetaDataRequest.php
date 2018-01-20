<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoMetaDataRequest extends FormRequest {

    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        return [
            'title' => 'required|max:30',
            'description' => 'max:1000',
            'visibility' => 'required|in:public,unlisted,private',
            'type' => 'required|in:free,paid',
            'extension' => 'required|in:3gp,h261,h263,mp4,mpeg,mov,webm,f4v,flv,m4v,mkv,avi,wmv'
        ];
    }

    public function messages() {
        return [
            'extension.in' => 'This video format is not supported.',
        ];
    }
}