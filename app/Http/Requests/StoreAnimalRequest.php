<?php
// app/Http/Requests/StoreAnimalRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  // agrega lógica de permisos si lo necesitas
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100'],
            'tag'           => ['required', 'string', 'max:20', 'unique:animals,tag'],
            'breed'         => ['required', 'string', 'max:50'],
            'age'           => ['required', 'integer', 'between:0,25'],
            'weight'        => ['nullable', 'numeric', 'min:0'], // peso opcional, pero si se proporciona debe ser numérico
            'health_status' => ['required', 'in:healthy,attention,sick'],
            'image'         => ['nullable', 'url'],
        ];
    }
}
