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
        Schema::table('company_settings', function (Blueprint $table) {
            $table->decimal('bpjs_kesehatan_capping', 15, 2)->default(12000000)->after('overtime_rate_per_hour'); // 12jt based on 2024
            $table->decimal('bpjs_jp_capping', 15, 2)->default(10042300)->after('bpjs_kesehatan_capping'); // 10,042,300 based on 2024
            $table->enum('overtime_formula', ['flat', 'depnaker'])->default('flat')->after('bpjs_jp_capping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'bpjs_kesehatan_capping',
                'bpjs_jp_capping',
                'overtime_formula',
            ]);
        });
    }
};
