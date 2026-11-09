<?php

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests must login to view employees', function () {
    $this->get(route('employees.index'))->assertRedirect(route('login'));
});

test('authenticated users can view dashboard HR with real data states', function () {
    $this->actingAs(User::factory()->create())->get(route('dashboard'))->assertOk()->assertSee('Dashboard HR')->assertSee('Total karyawan')->assertSee('Cuti menunggu approval');
});

test('users can search and view employees but cannot modify them or manage accounts', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create(['name' => 'Budi Santoso']);
    Employee::factory()->create(['name' => 'Siti']);
    $this->actingAs($user)->get(route('employees.index', ['search' => 'Budi']))->assertOk()->assertSee('Budi Santoso')->assertDontSee('Siti');
    $this->get(route('employees.show', $employee))->assertOk()->assertSee('Budi Santoso');
    $this->get(route('employees.create'))->assertForbidden();
    $this->post(route('employees.store'), [])->assertForbidden();
    $this->put(route('employees.update', $employee), [])->assertForbidden();
    $this->delete(route('employees.destroy', $employee))->assertForbidden();
    $this->get(route('users.index'))->assertForbidden();
});

test('admin can create update and delete an employee', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $data = Employee::factory()->make()->toArray();
    $this->actingAs($admin)->get(route('employees.create'))->assertOk();
    $this->post(route('employees.store'), $data)->assertRedirect(route('employees.index'));
    $employee = Employee::firstOrFail();
    $this->get(route('employees.edit', $employee))->assertOk();
    $data['name'] = 'Nama Baru';
    $this->put(route('employees.update', $employee), $data)->assertRedirect(route('employees.index'));
    $this->assertDatabaseHas('employees', ['id' => $employee->id, 'name' => 'Nama Baru']);
    $this->delete(route('employees.destroy', $employee))->assertRedirect(route('employees.index'));
    $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
});

test('employee uniqueness and required fields are validated', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employee = Employee::factory()->create();
    $this->actingAs($admin)->post(route('employees.store'), ['employee_number' => $employee->employee_number, 'email' => $employee->email])->assertSessionHasErrors(['employee_number', 'email', 'name']);
});

test('admin can create users but cannot delete own account', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->get(route('users.index'))->assertOk();
    $this->post(route('users.store'), ['name' => 'Test', 'email' => 'test@example.com', 'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'user'])->assertRedirect();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'role' => 'user']);
    $this->delete(route('users.destroy', $admin))->assertSessionHasErrors('user');
});

test('users can login and logout', function () {
    $user = User::factory()->create(['password' => 'password123']);
    $this->post(route('login.store'), ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
    $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password123'])->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
    $this->post(route('logout'))->assertRedirect(route('login'));
    $this->assertGuest();
});
