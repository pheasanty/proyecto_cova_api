<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MilkingSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'animal_id',
        'date',
        'start_time',
        'end_time',
        'yield',
        'quality',
        'notes',
        'temperature',
    ];

    protected $casts = [
        'date'        => 'date',
        'yield'       => 'decimal:2',
        'temperature' => 'decimal:1',
    ];

    /* ──────────────── Relaciones ──────────────── */

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
