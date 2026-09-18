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
        Schema::create('tax_ter_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Kategori A'
            $table->string('ptkp_list'); // e.g., 'TK/0, TK/1, K/0'
            $table->timestamps();
        });

        Schema::create('tax_ter_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_ter_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('min_bruto', 15, 2);
            $table->decimal('max_bruto', 15, 2)->nullable(); // null for 'and above'
            $table->decimal('percentage', 5, 2); // e.g., 2.50
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_ter_rates');
        Schema::dropIfExists('tax_ter_categories');
    }
};
