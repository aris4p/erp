<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employees = [
            ['name' => 'Aditya Pratama', 'gender' => 'Laki-laki', 'email' => 'aditya.pratama@example.com', 'phone' => '081234560101', 'birth_place' => 'Jakarta', 'address' => 'Jl. Melati No. 12, Jakarta Selatan', 'position' => 'Staf Operasional'],
            ['name' => 'Siti Nurhaliza', 'gender' => 'Perempuan', 'email' => 'siti.nurhaliza@example.com', 'phone' => '081234560102', 'birth_place' => 'Bandung', 'address' => 'Jl. Cendana No. 8, Bandung', 'position' => 'Staf Administrasi'],
            ['name' => 'Bima Saputra', 'gender' => 'Laki-laki', 'email' => 'bima.saputra@example.com', 'phone' => '081234560103', 'birth_place' => 'Surabaya', 'address' => 'Jl. Kenanga No. 21, Surabaya', 'position' => 'Analis Operasional'],
            ['name' => 'Dewi Lestari', 'gender' => 'Perempuan', 'email' => 'dewi.lestari@example.com', 'phone' => '081234560104', 'birth_place' => 'Yogyakarta', 'address' => 'Jl. Mawar No. 15, Yogyakarta', 'position' => 'Staf Keuangan'],
            ['name' => 'Fajar Ramadhan', 'gender' => 'Laki-laki', 'email' => 'fajar.ramadhan@example.com', 'phone' => '081234560105', 'birth_place' => 'Semarang', 'address' => 'Jl. Anggrek No. 6, Semarang', 'position' => 'Staf Gudang'],
            ['name' => 'Nadia Permata', 'gender' => 'Perempuan', 'email' => 'nadia.permata@example.com', 'phone' => '081234560106', 'birth_place' => 'Medan', 'address' => 'Jl. Flamboyan No. 9, Medan', 'position' => 'Staf SDM'],
            ['name' => 'Rizky Maulana', 'gender' => 'Laki-laki', 'email' => 'rizky.maulana@example.com', 'phone' => '081234560107', 'birth_place' => 'Makassar', 'address' => 'Jl. Dahlia No. 18, Makassar', 'position' => 'Staf Penjualan'],
            ['name' => 'Citra Ayuningtyas', 'gender' => 'Perempuan', 'email' => 'citra.ayuningtyas@example.com', 'phone' => '081234560108', 'birth_place' => 'Denpasar', 'address' => 'Jl. Kamboja No. 11, Denpasar', 'position' => 'Staf Layanan Pelanggan'],
        ];
        $employee = fake()->randomElement($employees);
        $employee['email'] = fake()->unique()->numerify(str($employee['email'])->before('@').'####@example.com');

        return [
            'employee_number' => fake()->unique()->numerify('EMP-######'),
            ...$employee,
            'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'department' => 'Operasional',
            'joined_at' => fake()->dateTimeBetween('-10 years', 'now'),
            'is_active' => true,
        ];
    }
}
