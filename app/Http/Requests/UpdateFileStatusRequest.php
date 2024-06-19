<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $status
 */
class UpdateFileStatusRequest extends FormRequest {
    public function rules(): array {
        return [
            'status' => 'required|string|in:uploaded,failed',
        ];
    }
}
