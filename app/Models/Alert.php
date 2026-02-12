<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Alert extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'type',
        'severity',
        'message',
        'animal_id',
        'date',
        'resolved',
        'resolved_at',
    ];

    protected $casts = [
        'date'        => 'datetime',
        'resolved'    => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
