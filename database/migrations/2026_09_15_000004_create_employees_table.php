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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('nik')->nullable();
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('employment_status_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->date('join_date');
            $table->date('resign_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('bpjs_ketenagakerjaan')->nullable();
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
