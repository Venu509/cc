<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_experiences', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('company');
            $table->string('job_title')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->boolean('is_still_working')->default(false);
            $table->longText('responsibilities')->nullable();
            $table->longText('achievements')->nullable();
            $table->longText('other_experiences')->nullable();
            $table->foreignIdFor(User::class, 'candidate_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
