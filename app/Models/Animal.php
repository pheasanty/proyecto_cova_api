<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Animal extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'tag',
        'breed',
        'age',
        'weight',
        'health_status',
        'image',
        'last_milking',
        'total_production',
        'average_daily',
    ];

    protected $casts = [
        'age'              => 'integer',
        'weight'           => 'float',
        'last_milking'     => 'datetime',
        'total_production' => 'decimal:2',
        'average_daily'    => 'decimal:2',
        'last_milking' => 'datetime',
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

    public function milkingSessions(): HasMany
    {
        return $this->hasMany(MilkingSession::class);
    }

        public function latestMilkingSession()
    {
        return $this->hasOne(MilkingSession::class)
                    ->latestOfMany('date');
    }
}
