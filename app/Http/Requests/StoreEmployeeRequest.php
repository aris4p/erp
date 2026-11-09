<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
            'employee_number' => ['required', 'string', 'max:255', Rule::unique('employees')],
            'name' => ['required', 'string', 'max:255'], 'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_place' => ['nullable', 'string', 'max:255'], 'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'address' => ['required', 'string'], 'phone' => ['required', 'string', 'max:30'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'], 'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees'), Rule::unique('users')],
            'marital_status' => ['nullable', Rule::in(['Belum menikah', 'Menikah', 'Cerai'])], 'education_level' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'], 'department' => ['required', 'string', 'max:255'], 'work_location' => ['nullable', 'string', 'max:255'], 'grade' => ['nullable', 'string', 'max:50'],
            'manager_id' => ['nullable', 'integer', Rule::exists('employees', 'id')], 'employment_status' => ['required', Rule::in(['Tetap', 'Kontrak', 'Magang'])],
            'annual_leave_balance' => ['required', 'integer', 'min:0', 'max:365'], 'joined_at' => ['required', 'date'], 'is_active' => ['required', 'boolean'],
        ];
    }
}
