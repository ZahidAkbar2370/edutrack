<?php

namespace Database\Factories;

use App\Models\DailyTest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyTest>
 */
class DailyTestFactory extends Factory
{
    protected $model = DailyTest::class;

    public function definition(): array
    {
        $total = fake()->randomElement([30, 40, 50, 75, 100]);
        $obtained = fake()->numberBetween(0, $total);
        $percentage = $total > 0 ? round(($obtained / $total) * 100, 2) : 0;

        return [
            'daily_test_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'daily_test_obtained' => $obtained,
            'daily_test_total' => $total,
            'daily_test_percentage' => $percentage,
            'daily_test_note' => fake()->optional(0.1)->sentence(),
        ];
    }
}
