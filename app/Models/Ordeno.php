<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordeno extends Model
{
    protected $fillable = [
        'vaca_id', 'fecha', 'hora', 'cantidad_litros', 'responsable'
    ];

    public function vaca() {
        return $this->belongsTo(Vaca::class);
    }
}
