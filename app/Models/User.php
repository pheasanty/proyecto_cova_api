<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'phone',
        'department',
        'join_date',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'join_date'  => 'date',
        'last_login' => 'datetime',
    ];

    /* ──────────────── Relaciones ──────────────── */

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }
}
