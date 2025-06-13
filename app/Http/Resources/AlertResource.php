<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'type'      => $this->type,
            'severity'  => $this->severity,
            'message'   => $this->message,
            'animalId'  => $this->animal_id,
            'date'      => $this->date->toIso8601String(),
            'resolved'  => (bool) $this->resolved,
        ];
    }
}

