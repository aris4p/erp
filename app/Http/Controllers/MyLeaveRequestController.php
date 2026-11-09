<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMyLeaveRequestRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MyLeaveRequestController extends Controller
{
    public function index(): View
    {
        $employee = request()->user()->employee()->with(['leaveRequests' => fn ($query) => $query->latest()])->firstOrFail();

        return view('my.leaves.index', compact('employee'));
    }

    public function create(): View
    {
        return view('my.leaves.create');
    }

    public function store(StoreMyLeaveRequestRequest $request): RedirectResponse
    {
        $employee = $request->user()->employee;
        $data = $request->validated();
        $data['days'] = $request->date('start_date')->diffInDays($request->date('end_date')) + 1;
        $data['status'] = $employee->manager_id ? 'Menunggu Atasan' : 'Menunggu HR';
        $employee->leaveRequests()->create($data);

        return redirect()->route('my.leaves.index')->with('success', 'Pengajuan berhasil dikirim.');
    }
}
