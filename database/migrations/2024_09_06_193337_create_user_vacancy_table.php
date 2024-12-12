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
        Schema::create('user_vacancy', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignIdFor( Vacancy::class, 'vacancy_id')->constrained('vacancies')->cascadeOnDelete();
            $table->foreignIdFor( User::class, 'candidate_id')->constrained('users')->cascadeOnDelete();
            $table->enum( 'status', ['applied', 'viewed', 'shortlisted', 'rejected'])->default('applied');
            $table->jsonb('history')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_vacancy');
    }
};
