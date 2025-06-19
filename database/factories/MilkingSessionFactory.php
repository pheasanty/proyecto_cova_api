<?php

namespace Database\Factories;

use App\Models\MilkingSession;
use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

class MilkingSessionFactory extends Factory
{
    protected $model = MilkingSession::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-7 days', 'now');
        $end = (clone $start)->modify('+15 minutes');

        return [
            'animal_id' => Animal::factory(),
            'date' => $start->format('Y-m-d'),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'milk_yield' => $this->faker->randomFloat(2, 5, 25),
            'quality' => $this->faker->randomElement(['excellent', 'good', 'fair', 'poor']),
            'notes' => $this->faker->optional()->sentence,
            'temperature' => $this->faker->randomFloat(1, 35.0, 39.5),
        ];
    }
}
