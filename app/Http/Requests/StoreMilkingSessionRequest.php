<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class StoreMilkingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'animal_id'   => ['required', 'uuid', 'exists:animals,id'],
            'date'        => ['required', 'date_format:Y-m-d'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i'], // ya no usamos after:start_time
            'milk_yield'  => ['required', 'numeric', 'between:0,200'],
            'quality'     => ['required', 'in:excellent,good,fair,poor'],
            'notes'       => ['nullable', 'string', 'max:500'],
            'temperature' => ['required', 'numeric', 'between:30,45'],
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            // Tomamos los valores limpios del request
            $date      = $this->input('date');
            $startTime = $this->input('start_time');
            $endTime   = $this->input('end_time');

            // Creamos dos instancias Carbon (fecha + hora)
            $start = Carbon::createFromFormat('Y-m-d H:i', "$date $startTime");
            $end   = Carbon::createFromFormat('Y-m-d H:i', "$date $endTime");

            // Si end <= start, asumimos que cruza medianoche y sumamos un día
            if ($end->lte($start)) {
                $end->addDay();
            }

            // Si aún así end no queda después de start, es error
            if (! $end->gt($start)) {
                $validator->errors()->add(
                    'end_time',
                    'La hora de fin debe ser posterior a la hora de inicio.'
                );
            }
        });
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException(
            $validator,
            response()->json([
                'message' => 'Errores de validación',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
