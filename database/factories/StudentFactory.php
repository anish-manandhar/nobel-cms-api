<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'user_id' => $this->faker->numberBetween(1,3),
            'faculty_id' => rand(1,2),
            'program_id' => rand(1,2),
            'semester_id' => rand(1,8),
            'roll_number' => $this->faker->numberBetween(1111111,9999999),
            'registration_number' => $this->faker->phoneNumber(),
            'guardian_name' => $this->faker->name(),
            'guardian_phone' => $this->faker->phoneNumber(),
            'joined_at' => now()
        ];
    }
}
