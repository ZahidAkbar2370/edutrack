<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'student_name' => fake()->name(),
            'student_email' => fake()->optional(0.7)->safeEmail(),
            'student_phone_no' => fake()->numerify('923#########'),
            'student_roll_number' => fake()->numerify('###'),
            'student_admission_date' => fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
        ];
    }
}
