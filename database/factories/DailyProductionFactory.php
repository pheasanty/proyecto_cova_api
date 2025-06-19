<?php


namespace Database\Factories;

use App\Models\DailyProduction;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyProductionFactory extends Factory
{
    protected $model = DailyProduction::class;

    public function definition(): array
    {
        $sessionCount = $this->faker->numberBetween(5, 20);
        $totalYield = $this->faker->randomFloat(2, $sessionCount * 5, $sessionCount * 25);

        return [
            'date' => $this->faker->unique()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'total_yield' => $totalYield,
            'session_count' => $sessionCount,
            'average_yield' => $totalYield / $sessionCount,
            'excellent' => $this->faker->numberBetween(0, $sessionCount),
            'good' => $this->faker->numberBetween(0, $sessionCount),
            'fair' => $this->faker->numberBetween(0, $sessionCount),
            'poor' => $this->faker->numberBetween(0, $sessionCount),
        ];
    }
}
