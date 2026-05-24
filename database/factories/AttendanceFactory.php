<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'attendance_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'attendance_status' => fake()->randomElement([
                'present', 'present', 'present', 'present',
                'absent', 'leave',
            ]),
            'attendance_note' => fake()->optional(0.15)->sentence(),
        ];
    }
}
