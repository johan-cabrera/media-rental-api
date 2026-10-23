<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'required', 'string', 'max:255'],
            'type'          => ['sometimes', 'required', 'string', Rule::in(['billboard', 'screen', 'mupi', 'wall'])],
            'location'      => ['sometimes', 'required', 'string', 'max:255'],
            'dimensions'    => ['sometimes', 'required', 'string', 'max:100'],
            'price_per_day' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status'        => ['sometimes', 'required', 'string', Rule::in(['active', 'inactive'])],
        ];
    }
}