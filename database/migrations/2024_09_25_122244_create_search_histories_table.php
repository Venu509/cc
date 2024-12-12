<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->enum('type', ['job', 'candidate'])->default('job');
            $table->string('praise');
            $table->integer('searched_times')->default(1);
            $table->integer('rating')->default(1);
            $table->foreignIdFor(User::class, 'user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};
