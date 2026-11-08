<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_number' => fake()->unique()->numerify('EMP-######'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'position' => fake()->jobTitle(),
            'department' => 'Operasional',
            'joined_at' => fake()->dateTimeBetween('-10 years', 'now'),
            'is_active' => true,
        ];
    }
}
