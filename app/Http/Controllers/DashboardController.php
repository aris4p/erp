<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\EmploymentContract;
use App\Models\LeaveRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = today();
        $activeEmployees = Employee::query()->where('is_active', true);
        $attendanceDates = collect(range(6, 0))->map(fn (int $daysAgo) => $today->copy()->subDays($daysAgo));
        $attendanceByDate = AttendanceRecord::query()
            ->whereBetween('attendance_date', [$attendanceDates->first(), $attendanceDates->last()])
            ->selectRaw("attendance_date, sum(case when status = 'Hadir' then 1 else 0 end) as present, sum(case when status = 'Terlambat' then 1 else 0 end) as late, sum(case when status not in ('Hadir', 'Terlambat') then 1 else 0 end) as absent")
            ->groupBy('attendance_date')
            ->get()
            ->keyBy(fn (AttendanceRecord $attendance) => $attendance->attendance_date->toDateString());

        $metrics = [
            'totalEmployees' => Employee::query()->count(),
            'activeEmployees' => (clone $activeEmployees)->count(),
            'presentToday' => (clone $activeEmployees)->whereHas('attendanceRecords', fn ($query) => $query->where('attendance_date', $today)->whereIn('status', ['Hadir', 'Terlambat']))->count(),
            'pendingLeaves' => LeaveRequest::query()->where('status', 'Menunggu')->count(),
        ];
        $departmentCounts = (clone $activeEmployees)->selectRaw('department, count(*) as total')->groupBy('department')->orderByDesc('total')->limit(6)->get();
        $recentEmployees = Employee::query()->with('manager')->latest('joined_at')->limit(5)->get();
        $pendingLeaveRequests = LeaveRequest::query()->with('employee')->where('status', 'Menunggu')->oldest()->limit(5)->get();
        $expiringDocuments = EmployeeDocument::query()->with('employee')->whereBetween('expires_at', [$today, $today->copy()->addDays(30)])->orderBy('expires_at')->limit(5)->get();
        $expiringContracts = EmploymentContract::query()->with('employee')->whereBetween('ends_at', [$today, $today->copy()->addDays(30)])->orderBy('ends_at')->limit(5)->get();
        $attendanceTrend = $attendanceDates->map(fn ($date) => [
            'label' => $date->translatedFormat('D'),
            'present' => $attendanceByDate->get($date->toDateString())?->present ?? 0,
            'late' => $attendanceByDate->get($date->toDateString())?->late ?? 0,
            'absent' => $attendanceByDate->get($date->toDateString())?->absent ?? 0,
        ]);
        $employmentStatuses = (clone $activeEmployees)->selectRaw('employment_status, count(*) as total')->groupBy('employment_status')->orderByDesc('total')->get();

        return view('dashboard', compact('metrics', 'departmentCounts', 'recentEmployees', 'pendingLeaveRequests', 'expiringDocuments', 'expiringContracts', 'attendanceTrend', 'employmentStatuses'));
    }
}
