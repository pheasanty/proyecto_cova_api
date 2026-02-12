<?php


namespace Database\Factories;

use App\Models\Alert;
use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        $messages = [
            'Baja producción detectada en el ordeño de la mañana.',
            'Vaca en celo detectada por actividad inusual.',
            'Mantenimiento preventivo de ordeñadora requerido.',
            'Nivel de temperatura en tanque de enfriamiento fuera de rango.',
            'Animal requiere chequeo veterinario rutinario.',
            'Vacuna pendiente para el grupo de terneros.',
            'Consumo de alimento por debajo del promedio detectado.',
            'Inicio de periodo seco programado para mañana.',
            'Alerta de mastitis clínica detectada.'
        ];

        return [
            'type' => $this->faker->randomElement(['health', 'schedule', 'production', 'maintenance']),
            'severity' => $this->faker->randomElement(['low', 'medium', 'high']),
            'message' => $this->faker->randomElement($messages),
            'animal_id' => $this->faker->optional()->randomElement([null, Animal::factory()]),
            'date' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'resolved' => $this->faker->boolean(30),
        ];
    }
}
