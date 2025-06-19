<?php

// database/seeders/DemoDataSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;
use App\Models\MilkingSession;
use App\Models\DailyProduction;
use App\Models\Alert;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 20 animales
        $animals = Animal::factory()->count(20)->create();

        // Crear 100 sesiones de ordeño, cada una ligada a un animal aleatorio
        MilkingSession::factory()->count(100)->make()->each(function ($session) use ($animals) {
            $session->animal_id = $animals->random()->id;
            $session->save();
        });

        // Crear 30 producciones diarias con fechas únicas consecutivas
        $startDate = Carbon::now()->subDays(30);
        for ($i = 0; $i < 30; $i++) {
            DailyProduction::factory()->create([
                'date' => $startDate->copy()->addDays($i)->format('Y-m-d'),
            ]);
        }

        // Crear 15 alertas, algunas con animal asignado, otras sin
        Alert::factory()->count(15)->make()->each(function ($alert) use ($animals) {
            if (rand(0, 1)) {
                $alert->animal_id = $animals->random()->id;
            }
            $alert->save();
        });

        $this->command->info('Datos de prueba creados sin conflictos de fecha ✅');
    }
}

