<?php

namespace App\Models;

use Database\Factories\EmploymentHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentHistory extends Model
{
    /** @use HasFactory<EmploymentHistoryFactory> */
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'department', 'position', 'grade', 'effective_date', 'notes'];

    protected function casts(): array
    {
        return ['effective_date' => 'date'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
