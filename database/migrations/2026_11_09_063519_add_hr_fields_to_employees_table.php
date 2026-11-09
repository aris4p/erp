<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('birth_place')->nullable()->after('gender');
            $table->string('emergency_contact_name')->nullable()->after('phone');
            $table->string('emergency_contact_phone', 30)->nullable()->after('emergency_contact_name');
            $table->string('marital_status')->nullable()->after('email');
            $table->string('education_level')->nullable()->after('marital_status');
            $table->string('work_location')->nullable()->after('department');
            $table->string('grade')->nullable()->after('work_location');
            $table->foreignId('manager_id')->nullable()->after('grade')->constrained('employees')->nullOnDelete();
            $table->string('employment_status')->default('Tetap')->after('manager_id');
            $table->unsignedInteger('annual_leave_balance')->default(12)->after('employment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('manager_id');
            $table->dropColumn([
                'birth_place',
                'emergency_contact_name',
                'emergency_contact_phone',
                'marital_status',
                'education_level',
                'work_location',
                'grade',
                'employment_status',
                'annual_leave_balance',
            ]);
        });
    }
};
