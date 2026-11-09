<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmploymentContractRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmploymentContractController extends Controller
{
    public function create(Employee $employee): View
    {
        return view('employees.contracts.create', compact('employee'));
    }

    public function store(StoreEmploymentContractRequest $request, Employee $employee): RedirectResponse
    {
        $employee->employmentContracts()->create($request->validated());

        return redirect()->route('employees.show', $employee)->with('success', 'Kontrak kerja berhasil disimpan.');
    }
}
