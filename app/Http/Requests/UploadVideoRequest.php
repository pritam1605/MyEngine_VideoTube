<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UploadVideoRequest extends FormRequest {

    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        return [
            'video' => 'mimes:3gpp,h261,h263,h264,mp4,mpeg,quicktime,webm,x-f4v,x-flv,x-m4v,x-matroska,x-ms-vob,x-ms-wmv,x-msvideo|max:102400',
        ];
    }

    public function messages() {
        return [
            'video.mimes' => 'This video format is not supported.',
        ];
    }
}