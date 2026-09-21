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
        Schema::table('leaves', function (Blueprint $table) {
            $table->foreignId('supervisor_approved_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
        });

        Schema::table('overtimes', function (Blueprint $table) {
            // Drop enum and recreate as string (SQLite doesn't support changing ENUM easily, but Laravel handles string changes sometimes)
            $table->string('status')->default('pending_supervisor')->change();
            $table->foreignId('supervisor_approved_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->foreignId('manager_approved_by')->nullable()->after('supervisor_approved_by')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['supervisor_approved_by']);
            $table->dropColumn('supervisor_approved_by');
        });

        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropForeign(['supervisor_approved_by']);
            $table->dropForeign(['manager_approved_by']);
            $table->dropColumn(['supervisor_approved_by', 'manager_approved_by']);
            // Revert status to enum
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
