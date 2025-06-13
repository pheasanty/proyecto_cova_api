<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilkingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pon tus policies si las necesitas
    }

    public function rules(): array
    {
        return [
            'animal_id'   => ['required', 'uuid', 'exists:animals,id'],
            'date'        => ['required', 'date'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
            'milk_yield'       => ['required', 'numeric', 'between:0,200'],
            'quality'     => ['required', 'in:excellent,good,fair,poor'],
            'notes'       => ['nullable', 'string', 'max:500'],
            'temperature' => ['required', 'numeric', 'between:30,45'],
        ];
    }
}
