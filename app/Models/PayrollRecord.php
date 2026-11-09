<?php

namespace App\Models;

use Database\Factories\PayrollRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollRecord extends Model
{
    /** @use HasFactory<PayrollRecordFactory> */
    use HasFactory;

    protected $fillable = ['employee_id', 'period', 'basic_salary', 'allowances', 'deductions', 'bpjs_health', 'bpjs_employment', 'overtime_pay', 'net_pay', 'paid_at', 'payslip_path'];

    protected function casts(): array
    {
        return ['period' => 'date', 'paid_at' => 'date', 'basic_salary' => 'decimal:2', 'allowances' => 'decimal:2', 'deductions' => 'decimal:2', 'bpjs_health' => 'decimal:2', 'bpjs_employment' => 'decimal:2', 'overtime_pay' => 'decimal:2', 'net_pay' => 'decimal:2'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
