<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $employees = Employee::query()->when($search !== '', function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')->orWhere('employee_number', 'like', '%'.$search.'%')->orWhere('department', 'like', '%'.$search.'%');
            });
        })->orderBy('name')->paginate(15)->withQueryString();

        return view('employees.index', compact('employees', 'search'));
    }

    public function create(): View
    {
        return view('employees.form', ['employee' => new Employee]);
    }

    public function store(Request $request): RedirectResponse
    {
        Employee::create($this->validated($request));

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        return view('employees.form', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $employee->update($this->validated($request, $employee));

        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }

    private function validated(Request $request, ?Employee $employee = null): array
    {
        return $request->validate([
            'employee_number' => ['required', 'string', 'max:255', Rule::unique('employees')->ignore($employee)],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees')->ignore($employee)],
            'position' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
