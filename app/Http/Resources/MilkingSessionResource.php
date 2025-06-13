<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilkingSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,

            // ----- Animal -----
            'animalId'    => $this->animal_id,
            'animalName'  => $this->whenLoaded('animal', fn () => $this->animal->name),
            'animalTag'   => $this->whenLoaded('animal', fn () => $this->animal->tag),

            // ----- Fechas y horas -----
            'date'        => $this->date->toDateString(), // "2024-03-25"
            'startTime'   => $this->start_time,              // "06:10"
            'endTime'     => $this->end_time,                // "06:25"

            // ----- Métricas -----
            'yield'       => (float) $this->yield,
            'quality'     => $this->quality,
            'temperature' => (float) $this->temperature,

            // ----- Otros -----
            'notes'       => $this->notes,
            'createdAt'   => $this->created_at?->toDateString(),
            'updatedAt'   => $this->updated_at?->toDateString(),
        ];
    }
}
