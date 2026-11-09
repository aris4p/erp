<?php

namespace App\Models;

use Database\Factories\PerformanceReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends Model
{
    /** @use HasFactory<PerformanceReviewFactory> */
    use HasFactory;

    protected $fillable = ['employee_id', 'reviewer_id', 'period', 'target', 'kpi', 'score', 'notes', 'reviewed_at'];

    protected function casts(): array
    {
        return ['reviewed_at' => 'date'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }
}
