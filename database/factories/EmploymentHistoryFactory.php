<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmploymentHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmploymentHistory>
 */
class EmploymentHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'type' => 'Promosi',
            'department' => 'Operasional',
            'position' => 'Staf Operasional',
            'grade' => 'G4',
            'effective_date' => now(),
            'notes' => null,
        ];
    }
}
