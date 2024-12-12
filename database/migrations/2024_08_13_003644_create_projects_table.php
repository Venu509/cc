<?php

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignIdFor(ProjectName::class, 'name')->constrained('projects_names');
            $table->enum('type', ['mini', 'academic'])->default('mini');
            $table->string('semester');
            $table->longText('description')->nullable();
            $table->longText('date');
            $table->longText('end_date');
            $table->foreignIdFor(Branch::class, 'branch_id')->constrained('branches');
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users');
            $table->foreignIdFor(User::class, 'added_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
