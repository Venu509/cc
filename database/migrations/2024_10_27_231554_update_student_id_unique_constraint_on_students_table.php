<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', static function (Blueprint $table) {
            $table->dropUnique(['student_id']);
            $table->unique(['student_id', 'added_by']);
        });
    }

    public function down(): void
    {
        Schema::table('students', static function (Blueprint $table) {
            $table->dropUnique(['student_id', 'added_by']);
            $table->unique('student_id');
        });
    }
};
