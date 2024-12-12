<?php

use Domain\Skill\Models\Skill;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_vacancy', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignIdFor(Vacancy::class, 'vacancy_id')->constrained('vacancies')->cascadeOnDelete();
            $table->foreignIdFor(Skill::class, 'skill_id')->constrained('skills')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_vacancy');
    }
};
