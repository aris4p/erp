<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRecordRequest extends FormRequest
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
            'attendance_date' => ['required', 'date'],
            'checked_in_at' => ['nullable', 'date_format:H:i'],
            'checked_out_at' => ['nullable', 'date_format:H:i'],
            'late_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'early_leave_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'overtime_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'status' => ['required', Rule::in(['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'])],
            'notes' => ['nullable', 'string'],
        ];
    }
}
