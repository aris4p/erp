<?php

namespace App\Models;

use Database\Factories\EmployeeExitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeExit extends Model
{
    /** @use HasFactory<EmployeeExitFactory> */
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'effective_date', 'reason', 'asset_status', 'document_status'];

    protected function casts(): array
    {
        return ['effective_date' => 'date'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
