<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'membership_id' => Membership::inRandomOrder()->first()->id,
            'school_name' => fake()->unique()->company(),
            'school_email' => fake()->unique()->safeEmail(),
            'school_phone_no' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'address' => fake()->address(),
            'priciple_name' => fake()->name(),
            'priciple_email' => fake()->unique()->safeEmail(),
            'priciple_phone_no' => fake()->phoneNumber(),
        ];
    }
}
