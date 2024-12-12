<?php

use App\Models\User;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_vacancies', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignIdFor(Vacancy::class, 'vacancy_id')->constrained('vacancies')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'candidate_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('saved_vacancies');
    }
};
