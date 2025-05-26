<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@mamaboba.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin'
        ]);

        User::create([
            'name' => 'Veterinario',
            'email' => 'vet@mamaboba.com',
            'password' => Hash::make('vet123'),
            'rol' => 'veterinario'
        ]);

        User::create([
            'name' => 'Ordeñador',
            'email' => 'operario@mamaboba.com',
            'password' => Hash::make('ordeño123'),
            'rol' => 'ordeñador'
        ]);
    }
}
