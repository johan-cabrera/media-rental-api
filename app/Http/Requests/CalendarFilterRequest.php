<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'media_id'   => ['nullable', 'exists:media,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.date'           => 'La fecha de inicio debe tener un formato de fecha válido.',
            'end_date.date'             => 'La fecha de fin debe tener un formato de fecha válido.',
            'end_date.after_or_equal'   => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'media_id.exists'           => 'El espacio publicitario seleccionado no existe.',
        ];
    }
}