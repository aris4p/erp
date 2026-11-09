<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveRequest>
 */
class LeaveRequestFactory extends Factory
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
            'type' => 'Cuti Tahunan',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeek(),
            'days' => 1,
            'reason' => 'Keperluan pribadi',
            'status' => 'Menunggu',
            'approved_by' => null,
            'approved_at' => null,
        ];
    }
}
