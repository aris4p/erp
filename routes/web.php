<?php

use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\EmployeeExitController;
use App\Http\Controllers\EmploymentContractController;
use App\Http\Controllers\EmploymentHistoryController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\MyLeaveRequestController;
use App\Http\Controllers\MyPortalController;
use App\Http\Controllers\PayrollRecordController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->middleware('can:manage-employees')->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/my/profile', [MyPortalController::class, 'profile'])->name('my.profile');
    Route::get('/my/attendance', [MyPortalController::class, 'attendance'])->name('my.attendance');
    Route::post('/my/attendance/check-in', [MyPortalController::class, 'checkIn'])->name('my.attendance.check-in');
    Route::post('/my/attendance/check-out', [MyPortalController::class, 'checkOut'])->name('my.attendance.check-out');
    Route::get('/my/payslips', [MyPortalController::class, 'payslips'])->name('my.payslips');
    Route::get('/my/payslips/{payrollRecord}', [MyPortalController::class, 'payslip'])->name('my.payslips.show');
    Route::get('/my/leaves', [MyLeaveRequestController::class, 'index'])->name('my.leaves.index');
    Route::get('/my/leaves/create', [MyLeaveRequestController::class, 'create'])->name('my.leaves.create');
    Route::post('/my/leaves', [MyLeaveRequestController::class, 'store'])->name('my.leaves.store');
    Route::get('/leave-approvals/manager', [LeaveRequestController::class, 'managerApprovals'])->name('leave-approvals.manager');
    Route::get('/leave-approvals/hr', [LeaveRequestController::class, 'hrApprovals'])->name('leave-approvals.hr');
    Route::patch('/leave-requests/{leaveRequest}/manager-approve', [LeaveRequestController::class, 'approveManager'])->name('leave-requests.manager-approve');
    Route::patch('/leave-requests/{leaveRequest}/hr-approve', [LeaveRequestController::class, 'approveHr'])->name('leave-requests.hr-approve');
    Route::middleware('can:manage-employees')->group(function () {
        Route::resource('employees', EmployeeController::class)->except(['index', 'show']);
        Route::get('/employees/{employee}/documents/create', [EmployeeDocumentController::class, 'create'])->name('employees.documents.create');
        Route::post('/employees/{employee}/documents', [EmployeeDocumentController::class, 'store'])->name('employees.documents.store');
        Route::get('/employee-documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('employee-documents.download');
        Route::delete('/employee-documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('employee-documents.destroy');
        Route::get('/employees/{employee}/attendance/create', [AttendanceRecordController::class, 'create'])->name('employees.attendance.create');
        Route::post('/employees/{employee}/attendance', [AttendanceRecordController::class, 'store'])->name('employees.attendance.store');
        Route::get('/employees/{employee}/leaves/create', [LeaveRequestController::class, 'create'])->name('employees.leaves.create');
        Route::post('/employees/{employee}/leaves', [LeaveRequestController::class, 'store'])->name('employees.leaves.store');
        Route::patch('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::patch('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        Route::get('/employees/{employee}/histories/create', [EmploymentHistoryController::class, 'create'])->name('employees.histories.create');
        Route::post('/employees/{employee}/histories', [EmploymentHistoryController::class, 'store'])->name('employees.histories.store');
        Route::get('/employees/{employee}/exit/create', [EmployeeExitController::class, 'create'])->name('employees.exit.create');
        Route::post('/employees/{employee}/exit', [EmployeeExitController::class, 'store'])->name('employees.exit.store');
        Route::get('/employees/{employee}/payroll/create', [PayrollRecordController::class, 'create'])->name('employees.payroll.create');
        Route::post('/employees/{employee}/payroll', [PayrollRecordController::class, 'store'])->name('employees.payroll.store');
        Route::get('/payroll-records/{payrollRecord}/payslip', [PayrollRecordController::class, 'payslip'])->name('payroll-records.payslip');
        Route::get('/employees/{employee}/reviews/create', [PerformanceReviewController::class, 'create'])->name('employees.reviews.create');
        Route::post('/employees/{employee}/reviews', [PerformanceReviewController::class, 'store'])->name('employees.reviews.store');
        Route::get('/employees/{employee}/contracts/create', [EmploymentContractController::class, 'create'])->name('employees.contracts.create');
        Route::post('/employees/{employee}/contracts', [EmploymentContractController::class, 'store'])->name('employees.contracts.store');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    Route::resource('employees', EmployeeController::class)->only(['index', 'show'])->middleware('can:manage-employees');
});
