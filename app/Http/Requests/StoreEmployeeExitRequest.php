<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeExitRequest extends FormRequest
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
            'type' => ['required', Rule::in(['Resign', 'Pemutusan Hubungan Kerja'])],
            'effective_date' => ['required', 'date'],
            'reason' => ['required', 'string'],
            'asset_status' => ['required', Rule::in(['Belum diperiksa', 'Lengkap', 'Belum lengkap'])],
            'document_status' => ['required', Rule::in(['Belum diperiksa', 'Lengkap', 'Belum lengkap'])],
        ];
    }
}
