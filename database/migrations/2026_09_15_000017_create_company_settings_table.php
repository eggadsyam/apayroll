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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Perusahaan');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('npwp')->nullable();
            $table->decimal('overtime_rate_per_hour', 15, 2)->default(0);
            $table->decimal('late_penalty_per_minute', 15, 2)->default(0);
            $table->integer('working_hours_per_day')->default(8);
            $table->time('default_clock_in')->default('08:00:00');
            $table->time('default_clock_out')->default('17:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
