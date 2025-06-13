<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'name'             => $this->faker->firstName(),
            'tag'              => 'A-' . Str::upper(Str::random(4)),
            'breed'            => $this->faker->randomElement(['Holstein', 'Jersey', 'Guernsey']),
            'age'              => $this->faker->numberBetween(1, 10),
            'health_status'    => $this->faker->randomElement(['healthy', 'sick', 'attention']),
            'last_milking'     => $this->faker->optional()->dateTimeBetween('-1 days', 'now'),
            'total_production' => 0,
            'average_daily'    => 0,
            'image'            => null,
        ];
    }
}
