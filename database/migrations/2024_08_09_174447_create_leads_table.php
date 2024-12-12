<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->enum('type', ['government', 'institution', 'candidate', 'company'])->default('government');
            $table->longText('status')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'added_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
