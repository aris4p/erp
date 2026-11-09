<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        return view('employees.form', ['employee' => new Employee, 'managers' => Employee::query()->orderBy('name')->get(['id', 'name', 'employee_number'])]);
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $employeeData = $request->validated();
            $user = User::query()->create([
                'name' => $employeeData['name'],
                'email' => $employeeData['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]);

            Employee::query()->create([...$employeeData, 'user_id' => $user->id]);
        });

        return redirect()->route('employees.index')->with('success', 'Karyawan dan akun login berhasil dibuat. Kata sandi awal: password.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['manager', 'attendanceRecords' => fn ($query) => $query->latest('attendance_date')->limit(10), 'leaveRequests' => fn ($query) => $query->latest(), 'employmentHistories' => fn ($query) => $query->latest('effective_date'), 'documents', 'employmentContracts' => fn ($query) => $query->latest('ends_at'), 'payrollRecords' => fn ($query) => $query->latest('period'), 'performanceReviews' => fn ($query) => $query->latest('reviewed_at'), 'exit']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $managers = Employee::query()->whereKeyNot($employee->id)->orderBy('name')->get(['id', 'name', 'employee_number']);

        return view('employees.form', compact('employee', 'managers'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        DB::transaction(function () use ($request, $employee): void {
            $employeeData = $request->validated();
            $employee->update($employeeData);
            $employee->user?->update(['name' => $employeeData['name'], 'email' => $employeeData['email']]);
        });

        return redirect()->route('employees.index')->with('success', 'Data karyawan dan akun login berhasil diperbarui.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
