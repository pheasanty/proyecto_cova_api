<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\MilkingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class MilkingSessionFactory extends Factory
{
    protected $model = MilkingSession::class;

    public function definition(): array
    {
        return [
            'animal_id'   => Animal::factory(),                 // crea (o usa) un animal
            'date'        => $this->faker->dateTimeBetween('-7 days', 'now')->format('Y-m-d'),
            'start_time'  => $this->faker->time('H:i'),
            'end_time'    => $this->faker->time('H:i'),
            'yield'       => $this->faker->randomFloat(2, 5, 25),   // litros
            'quality'     => $this->faker->randomElement(['excellent', 'good', 'fair', 'poor']),
            'notes'       => $this->faker->boolean(30) ? $this->faker->sentence() : null,
            'temperature' => $this->faker->randomFloat(1, 35, 42), // °C
        ];
    }
}
