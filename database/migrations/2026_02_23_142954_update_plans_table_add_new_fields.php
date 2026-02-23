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
        Schema::table('plans', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->enum('type', ['free', 'paid'])->default('paid')->after('description');
            $table->decimal('amount', 10, 2)->nullable()->after('type');
            $table->enum('duration', ['monthly', 'quarterly', 'half_yearly', 'yearly'])->default('monthly')->after('amount');
            $table->integer('max_services')->after('max_employees');
            $table->dropColumn(['price_monthly', 'price_yearly', 'storage_limit']);
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['description', 'type', 'amount', 'duration', 'max_services']);
            $table->decimal('price_monthly', 10, 2)->nullable();
            $table->decimal('price_yearly', 10, 2)->nullable();
            $table->string('storage_limit')->nullable();
        });
    }
};
