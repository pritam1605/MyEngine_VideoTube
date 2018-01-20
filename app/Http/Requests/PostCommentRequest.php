<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class PostCommentRequest extends FormRequest {

    public function authorize() {
        return !Auth::guest();
    }

    public function rules() {
        return [
            'body' => 'required|max:1000',
            'parent_id' => 'exists:comment,id',
        ];
    }
}
