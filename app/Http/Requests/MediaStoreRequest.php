<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'type'          => ['required', 'string', Rule::in(['billboard', 'screen', 'mupi', 'wall'])],
            'location'      => ['required', 'string', 'max:255'],
            'dimensions'    => ['required', 'string', 'max:100'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'status'        => ['sometimes', 'string', Rule::in(['active', 'inactive'])],
        ];
    }
}
