<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Animal extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'tag',
        'breed',
        'age',
        'health_status',
        'image',
        'last_milking',
        'total_production',
        'average_daily',
    ];

    protected $casts = [
        'age'              => 'integer',
        'last_milking'     => 'datetime',
        'total_production' => 'decimal:2',
        'average_daily'    => 'decimal:2',
    ];

    /* ──────────────── Relaciones ──────────────── */

    public function sessions()
    {
        return $this->hasMany(MilkingSession::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
