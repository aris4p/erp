<?php

namespace App\Models;

use Database\Factories\EmploymentContractFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentContract extends Model
{
    /** @use HasFactory<EmploymentContractFactory> */
    use HasFactory;

    protected $fillable = ['employee_id', 'number', 'type', 'starts_at', 'ends_at', 'file_path'];

    protected function casts(): array
    {
        return ['starts_at' => 'date', 'ends_at' => 'date'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
