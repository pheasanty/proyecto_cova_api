<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existe un admin
        $adminExists = User::where('email', 'admin@dairyflow.com')->exists();

        if ($adminExists) {
            $this->command->info('El usuario administrador ya existe.');
            return;
        }

        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@dairyflow.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'department' => 'Administración',
            'phone' => '+1234567890',
            'join_date' => now(),
        ]);

        $this->command->info('✅ Usuario administrador creado exitosamente:');
        $this->command->info('   Email: admin@dairyflow.com');
        $this->command->info('   Password: admin123');
        $this->command->warn('⚠️  Recuerda cambiar la contraseña después del primer login');
    }
}
