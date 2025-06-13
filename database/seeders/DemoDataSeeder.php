<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MilkingSession;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Esto creará 5 sesiones y, gracias a la relación, 5 animales.
        MilkingSession::factory()->count(5)->create();
    }
}
