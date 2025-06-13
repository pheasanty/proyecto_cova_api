<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * @return array
     */
    public function toArray($request): array   // ← sin type-hint en $request
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'tag'             => $this->tag,
            'breed'           => $this->breed,
            'age'             => $this->age,
            'healthStatus'    => $this->health_status,
            'lastMilking'     => $this->last_milking?->toIso8601String(),
            'totalProduction' => (float) $this->total_production,
            'averageDaily'    => (float) $this->average_daily,
            'image'           => $this->image,
            'createdAt'       => $this->created_at?->toIso8601String(),
            'updatedAt'       => $this->updated_at?->toIso8601String(),
        ];
    }
}
