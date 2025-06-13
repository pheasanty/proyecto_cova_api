<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyProduction extends Model
{
    use HasFactory;

    public $incrementing = false;          // PK = date
    protected $primaryKey = 'date';
    protected $keyType   = 'string';       // se guarda como YYYY-MM-DD

    protected $fillable = [
        'date',
        'total_yield',
        'session_count',
        'average_yield',
        'excellent',
        'good',
        'fair',
        'poor',
    ];

    protected $casts = [
        'date'          => 'date',
        'total_yield'   => 'decimal:2',
        'average_yield' => 'decimal:2',
        'session_count' => 'integer',
        'excellent'     => 'integer',
        'good'          => 'integer',
        'fair'          => 'integer',
        'poor'          => 'integer',
    ];
}
