<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_title' => $this->faker->randomElement(['Accountant', 'Guard', 'Director', 'Other']),
            'job_description' => $this->faker->text(10),
            'joined_at' => now(),
        ];
    }
}
