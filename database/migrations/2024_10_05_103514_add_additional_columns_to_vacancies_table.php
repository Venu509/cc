<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacancies', static function (Blueprint $table) {
            $table->boolean('is_walk_in_interview')->after('salary_frequency')->default(false);
            $table->dateTime('start_date')->after('is_walk_in_interview')->nullable();
            $table->dateTime('end_date')->after('start_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('vacancies', static function (Blueprint $table) {
            $table->dropColumn(['is_walk_in_interview', 'start_date', 'end_date']);
        });
    }
};
