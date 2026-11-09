<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeExitRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EmployeeExitController extends Controller
{
    public function create(Employee $employee): View
    {
        return view('employees.exits.create', compact('employee'));
    }

    public function store(StoreEmployeeExitRequest $request, Employee $employee): RedirectResponse
    {
        DB::transaction(function () use ($request, $employee): void {
            $employee->exit()->create($request->validated());
            $employee->update(['is_active' => false]);
        });

        return redirect()->route('employees.show', $employee)->with('success', 'Terminasi karyawan berhasil dicatat dan status karyawan dinonaktifkan.');
    }
}
