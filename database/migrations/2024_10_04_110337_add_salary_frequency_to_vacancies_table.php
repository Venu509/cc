<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacancies', static function (Blueprint $table) {
            $table->enum('salary_frequency', ['per-annum', 'per-month'])->after('is_active')->default('per-annum');
        });
    }

    public function down(): void
    {
        Schema::table('vacancies', static function (Blueprint $table) {
            $table->dropColumn(['salary_frequency']);
        });
    }
};
