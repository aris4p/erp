<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\EmploymentContract;
use App\Models\LeaveRequest;
use App\Models\PayrollRecord;
use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(['email' => 'admin@example.com'], ['name' => 'Budi Santoso', 'password' => Hash::make('password'), 'role' => 'admin']);
        $managerUser = User::query()->firstOrCreate(['email' => 'rina.wulandari@example.com'], ['name' => 'Rina Wulandari', 'password' => Hash::make('password'), 'role' => 'manager']);
        $manager = Employee::query()->firstOrCreate(['employee_number' => 'EMP-000001'], ['user_id' => $managerUser->id, 'name' => 'Rina Wulandari', 'gender' => 'Perempuan', 'birth_date' => '1988-04-12', 'address' => 'Jakarta', 'phone' => '081200000001', 'email' => 'rina.wulandari@example.com', 'position' => 'Manajer Operasional', 'department' => 'Operasional', 'joined_at' => '2016-01-11', 'is_active' => true, 'employment_status' => 'Tetap', 'annual_leave_balance' => 12]);
        $manager->update(['user_id' => $managerUser->id]);
        $hrUser = User::query()->firstOrCreate(['email' => 'hr@example.com'], ['name' => 'Nadia Permata', 'password' => Hash::make('password'), 'role' => 'hr']);
        $employees = collect([
            ['employee_number' => 'EMP-000002', 'name' => 'Aditya Pratama', 'gender' => 'Laki-laki', 'birth_place' => 'Jakarta', 'birth_date' => '1995-03-14', 'address' => 'Jl. Melati No. 12, Jakarta Selatan', 'phone' => '081234560101', 'email' => 'aditya.pratama@example.com', 'position' => 'Staf Operasional'],
            ['employee_number' => 'EMP-000003', 'name' => 'Siti Nurhaliza', 'gender' => 'Perempuan', 'birth_place' => 'Bandung', 'birth_date' => '1996-07-21', 'address' => 'Jl. Cendana No. 8, Bandung', 'phone' => '081234560102', 'email' => 'siti.nurhaliza@example.com', 'position' => 'Staf Administrasi'],
            ['employee_number' => 'EMP-000004', 'name' => 'Bima Saputra', 'gender' => 'Laki-laki', 'birth_place' => 'Surabaya', 'birth_date' => '1993-11-08', 'address' => 'Jl. Kenanga No. 21, Surabaya', 'phone' => '081234560103', 'email' => 'bima.saputra@example.com', 'position' => 'Analis Operasional'],
            ['employee_number' => 'EMP-000005', 'name' => 'Dewi Lestari', 'gender' => 'Perempuan', 'birth_place' => 'Yogyakarta', 'birth_date' => '1997-02-19', 'address' => 'Jl. Mawar No. 15, Yogyakarta', 'phone' => '081234560104', 'email' => 'dewi.lestari@example.com', 'position' => 'Staf Keuangan'],
            ['employee_number' => 'EMP-000006', 'name' => 'Fajar Ramadhan', 'gender' => 'Laki-laki', 'birth_place' => 'Semarang', 'birth_date' => '1994-09-27', 'address' => 'Jl. Anggrek No. 6, Semarang', 'phone' => '081234560105', 'email' => 'fajar.ramadhan@example.com', 'position' => 'Staf Gudang'],
            ['employee_number' => 'EMP-000007', 'name' => 'Nadia Permata', 'gender' => 'Perempuan', 'birth_place' => 'Medan', 'birth_date' => '1998-05-16', 'address' => 'Jl. Flamboyan No. 9, Medan', 'phone' => '081234560106', 'email' => 'nadia.permata@example.com', 'position' => 'Staf SDM'],
            ['employee_number' => 'EMP-000008', 'name' => 'Rizky Maulana', 'gender' => 'Laki-laki', 'birth_place' => 'Makassar', 'birth_date' => '1992-12-03', 'address' => 'Jl. Dahlia No. 18, Makassar', 'phone' => '081234560107', 'email' => 'rizky.maulana@example.com', 'position' => 'Staf Penjualan'],
            ['employee_number' => 'EMP-000009', 'name' => 'Citra Ayuningtyas', 'gender' => 'Perempuan', 'birth_place' => 'Denpasar', 'birth_date' => '1999-01-30', 'address' => 'Jl. Kamboja No. 11, Denpasar', 'phone' => '081234560108', 'email' => 'citra.ayuningtyas@example.com', 'position' => 'Staf Layanan Pelanggan'],
        ])->map(fn (array $attributes) => Employee::query()->firstOrCreate(
            ['employee_number' => $attributes['employee_number']],
            [...$attributes, 'manager_id' => $manager->id, 'department' => 'Operasional', 'employment_status' => 'Tetap', 'annual_leave_balance' => 12, 'joined_at' => '2020-01-01', 'is_active' => true],
        ));

        $employeeUser = User::query()->firstOrCreate(['email' => 'aditya.pratama@example.com'], ['name' => $employees->first()->name, 'password' => Hash::make('password'), 'role' => 'employee']);
        $employees->first()->update(['user_id' => $employeeUser->id]);
        $employees->firstWhere('employee_number', 'EMP-000007')?->update(['user_id' => $hrUser->id]);

        foreach ($employees as $index => $employee) {
            AttendanceRecord::factory()->create(['employee_id' => $employee->id, 'attendance_date' => today(), 'status' => $index === 0 ? 'Terlambat' : 'Hadir']);
            LeaveRequest::factory()->create(['employee_id' => $employee->id, 'status' => $index === 0 ? 'Menunggu Atasan' : ($index === 1 ? 'Menunggu HR' : 'Disetujui'), 'manager_approved_by' => $index === 1 ? $managerUser->id : null, 'manager_approved_at' => $index === 1 ? now() : null, 'approved_by' => $index < 2 ? null : $hrUser->id, 'approved_at' => $index < 2 ? null : now()]);
            EmploymentContract::query()->create(['employee_id' => $employee->id, 'number' => 'PKWT-2026-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT), 'type' => 'Kontrak', 'starts_at' => '2026-01-01', 'ends_at' => now()->addDays(20 + $index)]);
            EmployeeDocument::query()->create(['employee_id' => $employee->id, 'type' => 'KTP', 'document_number' => 'KTP-'.($index + 1), 'file_path' => 'employee-documents/example.pdf', 'expires_at' => now()->addDays(15 + $index)]);
            PayrollRecord::factory()->create(['employee_id' => $employee->id]);
            PerformanceReview::factory()->create(['employee_id' => $employee->id, 'reviewer_id' => $manager->id]);
        }
    }
}
