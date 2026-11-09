<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayrollRecordRequest;
use App\Models\Employee;
use App\Models\PayrollRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PayrollRecordController extends Controller
{
    public function create(Employee $employee): View
    {
        return view('employees.payroll.create', compact('employee'));
    }

    public function store(StorePayrollRecordRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();
        $data['period'] .= '-01';
        $data['net_pay'] = $data['basic_salary'] + $data['allowances'] + $data['overtime_pay'] - $data['deductions'] - $data['bpjs_health'] - $data['bpjs_employment'];

        $employee->payrollRecords()->updateOrCreate(['period' => $data['period']], $data);

        return redirect()->route('employees.show', $employee)->with('success', 'Payroll karyawan berhasil disimpan.');
    }

    public function payslip(PayrollRecord $payrollRecord): View
    {
        $payrollRecord->load('employee');

        return view('employees.payroll.payslip', compact('payrollRecord'));
    }
}
