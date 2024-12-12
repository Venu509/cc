<?php

use App\Models\User;
use Domain\Category\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('title', 200)->nullable();
            $table->string('slug', 200)->nullable();
            $table->string('salary')->nullable();
            $table->string('years_of_experiences')->nullable();
            $table->longText('description')->nullable();
            $table->jsonb('qualifications')->nullable();
            $table->jsonb('benefits')->nullable();
            $table->jsonb('work_modes')->nullable();
            $table->integer('no_of_openings')->default(1);
            $table->string('location', 120)->nullable();
            $table->date('expire_date')->nullable();
            $table->enum('application_method', ['internal', 'external'])->default('internal');
            $table->string('external_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(Category::class, 'category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'company_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'added_by')->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
