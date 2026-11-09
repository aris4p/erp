<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\PayrollRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollRecord>
 */
class PayrollRecordFactory extends Factory
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
            'period' => now()->startOfMonth(),
            'basic_salary' => 5_000_000,
            'allowances' => 500_000,
            'deductions' => 100_000,
            'bpjs_health' => 50_000,
            'bpjs_employment' => 100_000,
            'overtime_pay' => 200_000,
            'net_pay' => 5_450_000,
            'paid_at' => now(),
            'payslip_path' => null,
        ];
    }
}
