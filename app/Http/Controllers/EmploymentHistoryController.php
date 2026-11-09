<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmploymentHistoryRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmploymentHistoryController extends Controller
{
    public function create(Employee $employee): View
    {
        return view('employees.histories.create', compact('employee'));
    }

    public function store(StoreEmploymentHistoryRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();
        $employee->employmentHistories()->create($data);
        $employee->update(collect($data)->only(['department', 'position', 'grade'])->all());

        return redirect()->route('employees.show', $employee)->with('success', 'Riwayat organisasi berhasil disimpan.');
    }
}
