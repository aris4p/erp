<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyPortalController extends Controller
{
    public function profile(): View
    {
        $employee = request()->user()->employee()->with(['attendanceRecords' => fn ($query) => $query->latest('attendance_date')->limit(10), 'leaveRequests' => fn ($query) => $query->latest(), 'payrollRecords' => fn ($query) => $query->latest('period')])->firstOrFail();

        return view('my.profile', compact('employee'));
    }

    public function attendance(): View
    {
        $employee = request()->user()->employee;
        abort_unless($employee, 403);

        $todayAttendance = $employee->attendanceRecords()
            ->whereDate('attendance_date', today())
            ->first();
        $attendanceRecords = $employee->attendanceRecords()
            ->latest('attendance_date')
            ->paginate(15);

        return view('my.attendance', compact('attendanceRecords', 'todayAttendance'));
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;
        abort_unless($employee, 403);

        $attendance = $employee->attendanceRecords()->firstOrCreate(
            ['attendance_date' => today()->toDateString()],
            ['status' => 'Hadir'],
        );

        if ($attendance->checked_in_at !== null) {
            return back()->withErrors(['attendance' => 'Check-in hari ini sudah tercatat.']);
        }

        $attendance->update(['checked_in_at' => now()->format('H:i:s')]);

        return back()->with('success', 'Check-in berhasil dicatat.');
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;
        abort_unless($employee, 403);

        $attendance = $employee->attendanceRecords()
            ->whereDate('attendance_date', today())
            ->first();

        if ($attendance === null || $attendance->checked_in_at === null) {
            return back()->withErrors(['attendance' => 'Lakukan check-in sebelum check-out.']);
        }

        if ($attendance->checked_out_at !== null) {
            return back()->withErrors(['attendance' => 'Check-out hari ini sudah tercatat.']);
        }

        $attendance->update(['checked_out_at' => now()->format('H:i:s')]);

        return back()->with('success', 'Check-out berhasil dicatat.');
    }

    public function payslips(): View
    {
        $employee = request()->user()->employee;
        abort_unless($employee, 403);

        $payrollRecords = $employee->payrollRecords()
            ->latest('period')
            ->paginate(15);

        return view('my.payslips.index', compact('payrollRecords'));
    }

    public function payslip(PayrollRecord $payrollRecord): View
    {
        $employee = request()->user()->employee;
        abort_unless($employee, 403);

        $payrollRecord = $employee->payrollRecords()
            ->whereKey($payrollRecord)
            ->firstOrFail();

        return view('my.payslips.show', compact('payrollRecord', 'employee'));
    }
}
