<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['employee_number', 'name', 'gender', 'birth_date', 'address', 'phone', 'email', 'position', 'department', 'joined_at', 'is_active'];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'joined_at' => 'date', 'is_active' => 'boolean'];
    }

    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;
}
