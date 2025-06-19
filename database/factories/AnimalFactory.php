<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'tag' => $this->faker->unique()->bothify('TAG-###??'),
            'breed' => $this->faker->optional()->word,
            'age' => $this->faker->optional()->numberBetween(1, 15),
            'weight' => $this->faker->optional()->randomFloat(1, 200, 800),
            'health_status' => $this->faker->randomElement(['healthy', 'sick', 'attention']),
            'last_milking' => $this->faker->optional()->dateTimeThisMonth,
            'total_production' => $this->faker->randomFloat(2, 1000, 10000),
            'average_daily' => $this->faker->randomFloat(2, 10, 40),
            'image' => $this->faker->optional()->imageUrl(640, 480, 'animals'),
        ];
    }
}
