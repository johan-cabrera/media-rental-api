<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') instanceof \App\Models\User 
            ? $this->route('user')->id 
            : $this->route('user');

        return [
            'name'      => ['sometimes', 'required', 'string', 'max:255'],
            'email'     => [
                            'sometimes',
                            'required',
                            'string',
                            'email',
                            'max:255',
                            Rule::unique('users', 'email')->ignore($this->route('user')),
                        ],
            'password'  => ['nullable', 'string', 'min:6'],
            'role'      => ['sometimes', 'required', 'string', 'in:admin,user'],
        ];
    }
}