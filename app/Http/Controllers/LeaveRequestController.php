<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveHrLeaveRequestRequest;
use App\Http\Requests\ApproveLeaveRequestRequest;
use App\Http\Requests\ApproveManagerLeaveRequestRequest;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function managerApprovals(): View
    {
        $user = request()->user();
        abort_unless($user->hasRole('manager', 'admin'), 403);

        $leaveRequests = LeaveRequest::query()
            ->with('employee')
            ->where('status', 'Menunggu Atasan')
            ->when(! $user->isAdmin(), fn ($query) => $query->whereHas('employee', fn ($query) => $query->where('manager_id', $user->employee?->id)))
            ->oldest()
            ->paginate(15);

        return view('leave-approvals.manager', compact('leaveRequests'));
    }

    public function hrApprovals(): View
    {
        abort_unless(request()->user()->hasRole('hr', 'admin'), 403);

        $leaveRequests = LeaveRequest::query()
            ->with('employee.manager')
            ->where('status', 'Menunggu HR')
            ->oldest()
            ->paginate(15);

        return view('leave-approvals.hr', compact('leaveRequests'));
    }

    public function create(Employee $employee): View
    {
        return view('employees.leaves.create', compact('employee'));
    }

    public function store(StoreLeaveRequestRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();
        $data['days'] = $request->date('start_date')->diffInDays($request->date('end_date')) + 1;

        $employee->leaveRequests()->create($data);

        return redirect()->route('employees.show', $employee)->with('success', 'Pengajuan cuti atau izin berhasil dibuat dan menunggu approval HR.');
    }

    public function approve(ApproveLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if ($leaveRequest->status !== 'Menunggu') {
            return back()->withErrors(['leave_request' => 'Pengajuan ini sudah diproses.']);
        }

        DB::transaction(function () use ($leaveRequest, $request): void {
            $lockedRequest = LeaveRequest::query()->lockForUpdate()->findOrFail($leaveRequest->id);
            $employee = Employee::query()->lockForUpdate()->findOrFail($lockedRequest->employee_id);

            if ($lockedRequest->status !== 'Menunggu') {
                abort(422, 'Pengajuan ini sudah diproses.');
            }

            if ($lockedRequest->type === 'Cuti Tahunan' && $employee->annual_leave_balance < $lockedRequest->days) {
                abort(422, 'Saldo cuti karyawan tidak mencukupi.');
            }

            if ($lockedRequest->type === 'Cuti Tahunan') {
                $employee->decrement('annual_leave_balance', $lockedRequest->days);
            }

            $lockedRequest->update(['status' => 'Disetujui', 'approved_by' => $request->user()->id, 'approved_at' => now()]);
        }, attempts: 3);

        return back()->with('success', 'Pengajuan cuti atau izin disetujui.');
    }

    public function reject(ApproveLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if ($leaveRequest->status !== 'Menunggu') {
            return back()->withErrors(['leave_request' => 'Pengajuan ini sudah diproses.']);
        }

        $leaveRequest->update(['status' => 'Ditolak', 'approved_by' => $request->user()->id, 'approved_at' => now()]);

        return back()->with('success', 'Pengajuan cuti atau izin ditolak.');
    }

    public function approveManager(ApproveManagerLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $manager = $request->user()->employee;
        abort_unless($request->user()->isAdmin() || $manager?->id === $leaveRequest->employee->manager_id, 403);
        abort_unless($leaveRequest->status === 'Menunggu Atasan', 422, 'Pengajuan tidak menunggu approval atasan.');

        $leaveRequest->update(['status' => 'Menunggu HR', 'manager_approved_by' => $request->user()->id, 'manager_approved_at' => now()]);

        return back()->with('success', 'Pengajuan diteruskan ke HR.');
    }

    public function approveHr(ApproveHrLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless($leaveRequest->status === 'Menunggu HR', 422, 'Pengajuan tidak menunggu approval HR.');

        DB::transaction(function () use ($leaveRequest, $request): void {
            $lockedRequest = LeaveRequest::query()->lockForUpdate()->findOrFail($leaveRequest->id);
            $employee = Employee::query()->lockForUpdate()->findOrFail($lockedRequest->employee_id);

            if ($lockedRequest->type === 'Cuti Tahunan' && $employee->annual_leave_balance < $lockedRequest->days) {
                abort(422, 'Saldo cuti karyawan tidak mencukupi.');
            }

            if ($lockedRequest->type === 'Cuti Tahunan') {
                $employee->decrement('annual_leave_balance', $lockedRequest->days);
            }

            $lockedRequest->update(['status' => 'Disetujui', 'approved_by' => $request->user()->id, 'approved_at' => now()]);
        });

        return back()->with('success', 'Pengajuan disetujui HR.');
    }
}
