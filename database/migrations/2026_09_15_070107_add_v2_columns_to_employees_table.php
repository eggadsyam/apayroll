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
            $table->string('ptkp_status')->default('TK/0')->after('basic_salary');
            $table->enum('tax_method', ['gross', 'gross_up', 'nett'])->default('gross')->after('ptkp_status');
            $table->boolean('is_bpjs_kesehatan_active')->default(true)->after('bpjs_kesehatan');
            $table->boolean('is_bpjs_ketenagakerjaan_active')->default(true)->after('bpjs_ketenagakerjaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'ptkp_status',
                'tax_method',
                'is_bpjs_kesehatan_active',
                'is_bpjs_ketenagakerjaan_active',
            ]);
        });
    }
};
