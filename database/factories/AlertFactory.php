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
        return [
            'type' => $this->faker->randomElement(['health', 'schedule', 'production', 'maintenance']),
            'severity' => $this->faker->randomElement(['low', 'medium', 'high']),
            'message' => $this->faker->sentence,
            'animal_id' => $this->faker->optional()->randomElement([null, Animal::factory()]),
            'date' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'resolved' => $this->faker->boolean(30),
        ];
    }
}
