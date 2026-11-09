<?php

namespace App\Models;

use Database\Factories\LeaveRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    /** @use HasFactory<LeaveRequestFactory> */
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'start_date', 'end_date', 'days', 'reason', 'status', 'approved_by', 'approved_at', 'manager_approved_by', 'manager_approved_at'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'approved_at' => 'datetime', 'manager_approved_at' => 'datetime'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
