<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->dateTime('clock_in_at')->nullable();
            $table->dateTime('clock_out_at')->nullable();
            $table->ipAddress('clock_in_ip')->nullable();
            $table->ipAddress('clock_out_ip')->nullable();
            $table->jsonb('clock_in_coordinates')->nullable();
            $table->jsonb('clock_out_coordinates')->nullable();
            $table->enum('working_from', ['office', 'home', 'other'])->default('office');
            $table->boolean('is_completed')->default(false);
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'added_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
