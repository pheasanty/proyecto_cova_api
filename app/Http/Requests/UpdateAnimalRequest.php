<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnimalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
// app/Http/Requests/UpdateAnimalRequest.php
            public function rules(): array
            {
                $id = $this->route('animal')->id;

                return [
                    'name'          => ['sometimes', 'string', 'max:100'],
                    'tag'           => ['sometimes', 'string', 'max:20', Rule::unique('animals', 'tag')->ignore($id)],
                    'breed'         => ['sometimes', 'string', 'max:50'],
                    'age'           => ['sometimes', 'integer', 'between:0,25'],
                    'health_status' => ['sometimes', 'in:healthy,attention,sick'],
                    'image'         => ['nullable', 'url'],
                ];
            }

}
