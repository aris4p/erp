<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePerformanceReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-employees') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'reviewer_id' => ['nullable', 'integer', Rule::exists('employees', 'id')],
            'period' => ['required', 'string', 'max:100'],
            'target' => ['nullable', 'string'],
            'kpi' => ['nullable', 'string'],
            'score' => ['nullable', 'integer', 'min:1', 'max:100'],
            'notes' => ['nullable', 'string'],
            'reviewed_at' => ['required', 'date'],
        ];
    }
}
