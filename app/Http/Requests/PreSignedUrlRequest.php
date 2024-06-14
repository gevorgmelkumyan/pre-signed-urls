<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string file_name
 * @property string file_type
 */
class PreSignedUrlRequest extends FormRequest {
    public function rules(): array {
        return [
            'file_name' => 'required|string',
            'file_type' => 'required|string',
        ];
    }
}
