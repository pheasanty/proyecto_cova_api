<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaca extends Model
{
    protected $fillable = ['nombre', 'raza', 'fecha_nacimiento', 'estado'];

    public function ordenos()
    {
        return $this->hasMany(Ordeno::class);
    }
}
