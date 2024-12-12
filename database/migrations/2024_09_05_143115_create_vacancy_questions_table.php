<?php

use Domain\Vacancy\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancy_question', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->text('question');
            $table->jsonb('answers')->nullable();
            $table->foreignIdFor(Vacancy::class, 'vacancy_id')->constrained('vacancies');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancy_question');
    }
};
