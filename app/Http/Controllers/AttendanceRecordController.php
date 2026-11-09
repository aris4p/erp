<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRecordRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceRecordController extends Controller
{
    public function create(Employee $employee): View
    {
        return view('employees.attendance.create', compact('employee'));
    }

    public function store(StoreAttendanceRecordRequest $request, Employee $employee): RedirectResponse
    {
        $employee->attendanceRecords()->updateOrCreate(
            ['attendance_date' => $request->date('attendance_date')->toDateString()],
            $request->safe()->except('attendance_date'),
        );

        return redirect()->route('employees.show', $employee)->with('success', 'Absensi karyawan berhasil disimpan.');
    }
}
