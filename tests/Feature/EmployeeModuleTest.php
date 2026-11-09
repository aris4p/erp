<?php

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\LeaveRequest;
use App\Models\PayrollRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin can store complete employee master data', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $manager = Employee::factory()->create();

    $data = [
        ...Employee::factory()->make()->toArray(),
        'birth_place' => 'Bandung',
        'emergency_contact_name' => 'Kontak Darurat',
        'emergency_contact_phone' => '081234567890',
        'marital_status' => 'Menikah',
        'education_level' => 'S1',
        'work_location' => 'Kantor Pusat',
        'grade' => 'G5',
        'manager_id' => $manager->id,
        'employment_status' => 'Tetap',
        'annual_leave_balance' => 12,
    ];

    $this->actingAs($admin)->post(route('employees.store'), $data)->assertRedirect(route('employees.index'));

    $this->assertDatabaseHas('employees', [
        'birth_place' => 'Bandung',
        'manager_id' => $manager->id,
        'employment_status' => 'Tetap',
        'annual_leave_balance' => 12,
    ]);
    $this->assertDatabaseHas('users', ['email' => $data['email'], 'name' => $data['name'], 'role' => 'employee']);
});

test('employee detail loads related HR records', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create(['annual_leave_balance' => 8]);
    AttendanceRecord::factory()->create(['employee_id' => $employee->id]);
    LeaveRequest::factory()->create(['employee_id' => $employee->id]);
    EmploymentHistory::factory()->create(['employee_id' => $employee->id]);

    $this->actingAs($user)->get(route('employees.show', $employee))
        ->assertOk()
        ->assertSee('Saldo cuti tahunan')
        ->assertSee('Absensi terakhir')
        ->assertSee('Riwayat mutasi dan promosi');
});

test('admin can upload employee document', function () {
    Storage::fake('local');
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create();

    $this->actingAs($admin)->post(route('employees.documents.store', $employee), [
        'type' => 'KTP',
        'document_number' => '3273-1234',
        'file' => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'),
    ])->assertRedirect(route('employees.show', $employee));

    $document = $employee->documents()->firstOrFail();
    expect($document->type)->toBe('KTP');
    Storage::disk('local')->assertExists($document->file_path);
});

test('admin can save one attendance record per employee and date', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create();
    $data = ['attendance_date' => '2026-11-09', 'checked_in_at' => '08:15', 'checked_out_at' => '17:00', 'late_minutes' => 15, 'early_leave_minutes' => 0, 'overtime_minutes' => 30, 'status' => 'Terlambat'];

    $this->actingAs($admin)->post(route('employees.attendance.store', $employee), $data)->assertRedirect(route('employees.show', $employee));

    $this->assertDatabaseHas('attendance_records', ['employee_id' => $employee->id, 'attendance_date' => '2026-11-09', 'late_minutes' => 15]);
});

test('admin can approve annual leave and deduct employee leave balance', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create(['annual_leave_balance' => 12]);
    $leaveRequest = LeaveRequest::factory()->create(['employee_id' => $employee->id, 'type' => 'Cuti Tahunan', 'days' => 3]);

    $this->actingAs($admin)->patch(route('leave-requests.approve', $leaveRequest))->assertRedirect();

    $this->assertDatabaseHas('leave_requests', ['id' => $leaveRequest->id, 'status' => 'Disetujui', 'approved_by' => $admin->id]);
    $this->assertDatabaseHas('employees', ['id' => $employee->id, 'annual_leave_balance' => 9]);
});

test('admin cannot approve annual leave when balance is insufficient', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create(['annual_leave_balance' => 1]);
    $leaveRequest = LeaveRequest::factory()->create(['employee_id' => $employee->id, 'type' => 'Cuti Tahunan', 'days' => 3]);

    $this->actingAs($admin)->patch(route('leave-requests.approve', $leaveRequest))->assertUnprocessable();

    $this->assertDatabaseHas('leave_requests', ['id' => $leaveRequest->id, 'status' => 'Menunggu']);
    $this->assertDatabaseHas('employees', ['id' => $employee->id, 'annual_leave_balance' => 1]);
});

test('admin can record promotion and update active organization data', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create(['department' => 'Operasional', 'position' => 'Staf', 'grade' => 'G3']);

    $this->actingAs($admin)->post(route('employees.histories.store', $employee), ['type' => 'Promosi', 'department' => 'Keuangan', 'position' => 'Supervisor Keuangan', 'grade' => 'G5', 'effective_date' => '2026-11-09'])->assertRedirect(route('employees.show', $employee));

    $this->assertDatabaseHas('employment_histories', ['employee_id' => $employee->id, 'type' => 'Promosi', 'position' => 'Supervisor Keuangan']);
    $this->assertDatabaseHas('employees', ['id' => $employee->id, 'department' => 'Keuangan', 'position' => 'Supervisor Keuangan', 'grade' => 'G5']);
});

test('admin can record employee resignation and deactivate employee', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create(['is_active' => true]);

    $this->actingAs($admin)->post(route('employees.exit.store', $employee), ['type' => 'Resign', 'effective_date' => '2026-11-09', 'reason' => 'Pindah domisili', 'asset_status' => 'Lengkap', 'document_status' => 'Lengkap'])->assertRedirect(route('employees.show', $employee));

    $this->assertDatabaseHas('employee_exits', ['employee_id' => $employee->id, 'type' => 'Resign', 'asset_status' => 'Lengkap']);
    $this->assertDatabaseHas('employees', ['id' => $employee->id, 'is_active' => false]);
});

test('admin can save payroll and net pay is calculated on server', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create();
    $data = ['period' => '2026-11', 'basic_salary' => 5_000_000, 'allowances' => 500_000, 'overtime_pay' => 200_000, 'deductions' => 100_000, 'bpjs_health' => 50_000, 'bpjs_employment' => 100_000, 'paid_at' => '2026-11-30'];

    $this->actingAs($admin)->post(route('employees.payroll.store', $employee), $data)->assertRedirect(route('employees.show', $employee));

    $this->assertDatabaseHas('payroll_records', ['employee_id' => $employee->id, 'period' => '2026-11-01', 'net_pay' => 5_450_000]);
});

test('admin can save employee performance review', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create();
    $reviewer = Employee::factory()->create();
    $data = ['reviewer_id' => $reviewer->id, 'period' => 'Semester 2 2026', 'target' => 'Target penjualan tercapai', 'kpi' => 'Realisasi KPI 95 persen', 'score' => 95, 'notes' => 'Kinerja sangat baik', 'reviewed_at' => '2026-11-09'];

    $this->actingAs($admin)->post(route('employees.reviews.store', $employee), $data)->assertRedirect(route('employees.show', $employee));

    $this->assertDatabaseHas('performance_reviews', ['employee_id' => $employee->id, 'reviewer_id' => $reviewer->id, 'period' => 'Semester 2 2026', 'score' => 95]);
});

test('employee can submit own leave request through portal', function () {
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->post(route('my.leaves.store'), ['type' => 'Cuti Tahunan', 'start_date' => '2026-12-01', 'end_date' => '2026-12-03', 'reason' => 'Keperluan keluarga'])->assertRedirect(route('my.leaves.index'));

    $this->assertDatabaseHas('leave_requests', ['employee_id' => $employee->id, 'days' => 3, 'status' => 'Menunggu HR']);
});

test('employee can check in and check out only once each day', function () {
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create(['user_id' => $user->id]);

    $this->travelTo('2026-11-09 08:15:00', function () use ($user, $employee): void {
        $this->actingAs($user)->post(route('my.attendance.check-in'))
            ->assertRedirect(route('my.attendance'));

        $this->assertDatabaseHas('attendance_records', [
            'employee_id' => $employee->id,
            'attendance_date' => '2026-11-09',
            'checked_in_at' => '08:15:00',
            'status' => 'Hadir',
        ]);

        $this->actingAs($user)->post(route('my.attendance.check-in'))
            ->assertSessionHasErrors('attendance');

        $this->travelTo('2026-11-09 17:05:00');

        $this->actingAs($user)->post(route('my.attendance.check-out'))
            ->assertRedirect(route('my.attendance'));

        $this->assertDatabaseHas('attendance_records', [
            'employee_id' => $employee->id,
            'attendance_date' => '2026-11-09',
            'checked_out_at' => '17:05:00',
        ]);

        $this->actingAs($user)->post(route('my.attendance.check-out'))
            ->assertSessionHasErrors('attendance');
    });
});

test('employee can only view own payslip', function () {
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $ownPayroll = PayrollRecord::factory()->create(['employee_id' => $employee->id]);
    $otherPayroll = PayrollRecord::factory()->create();

    $this->actingAs($user)->get(route('my.payslips'))
        ->assertOk()
        ->assertSee($ownPayroll->period->translatedFormat('F Y'));

    $this->actingAs($user)->get(route('my.payslips.show', $ownPayroll))
        ->assertOk()
        ->assertSee($employee->employee_number);

    $this->actingAs($user)->get(route('my.payslips.show', $otherPayroll))
        ->assertNotFound();
});

test('employee cannot access HR dashboard, employee list, or user management', function () {
    $user = User::factory()->create(['role' => 'employee']);
    Employee::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get(route('dashboard'))->assertForbidden();
    $this->actingAs($user)->get(route('employees.index'))->assertForbidden();
    $this->actingAs($user)->get(route('users.index'))->assertForbidden();
});

test('manager approves direct report leave before HR approves it', function () {
    $managerUser = User::factory()->create(['role' => 'manager']);
    $manager = Employee::factory()->create(['user_id' => $managerUser->id]);
    $hrUser = User::factory()->create(['role' => 'hr']);
    $employee = Employee::factory()->create(['manager_id' => $manager->id, 'annual_leave_balance' => 12]);
    $leaveRequest = LeaveRequest::factory()->create(['employee_id' => $employee->id, 'status' => 'Menunggu Atasan', 'days' => 3]);

    $this->actingAs($managerUser)->get(route('leave-approvals.manager'))
        ->assertOk()
        ->assertSee($employee->name);

    $this->actingAs($managerUser)->patch(route('leave-requests.manager-approve', $leaveRequest))
        ->assertRedirect();

    $this->assertDatabaseHas('leave_requests', ['id' => $leaveRequest->id, 'status' => 'Menunggu HR', 'manager_approved_by' => $managerUser->id]);

    $this->actingAs($hrUser)->get(route('leave-approvals.hr'))
        ->assertOk()
        ->assertSee($employee->name);

    $this->actingAs($hrUser)->patch(route('leave-requests.hr-approve', $leaveRequest))
        ->assertRedirect();

    $this->assertDatabaseHas('leave_requests', ['id' => $leaveRequest->id, 'status' => 'Disetujui', 'approved_by' => $hrUser->id]);
    $this->assertDatabaseHas('employees', ['id' => $employee->id, 'annual_leave_balance' => 9]);
});

test('HR cannot approve leave before manager approval', function () {
    $hrUser = User::factory()->create(['role' => 'hr']);
    $leaveRequest = LeaveRequest::factory()->create(['status' => 'Menunggu Atasan']);

    $this->actingAs($hrUser)->patch(route('leave-requests.hr-approve', $leaveRequest))
        ->assertUnprocessable();

    $this->assertDatabaseHas('leave_requests', ['id' => $leaveRequest->id, 'status' => 'Menunggu Atasan']);
});
