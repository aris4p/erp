<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerformanceReviewRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PerformanceReviewController extends Controller
{
    public function create(Employee $employee): View
    {
        $reviewers = Employee::query()->whereKeyNot($employee->id)->orderBy('name')->get(['id', 'name', 'employee_number']);

        return view('employees.reviews.create', compact('employee', 'reviewers'));
    }

    public function store(StorePerformanceReviewRequest $request, Employee $employee): RedirectResponse
    {
        $employee->performanceReviews()->create($request->validated());

        return redirect()->route('employees.show', $employee)->with('success', 'Penilaian kinerja berhasil disimpan.');
    }
}
