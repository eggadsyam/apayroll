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
        Schema::create('employee_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('loan_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('installment', 15, 2);
            $table->decimal('remaining_balance', 15, 2);
            $table->integer('total_installments');
            $table->integer('paid_installments')->default(0);
            $table->enum('status', ['active', 'paid_off', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_loans');
    }
};
