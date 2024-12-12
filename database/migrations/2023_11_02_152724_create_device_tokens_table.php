<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('device_tokens', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignIdFor(User::class, 'user_id')->constrained('users');
            $table->string('device_token');
            $table->enum('device_type', ['android', 'ios', 'web'])->default('android');
            $table->timestamps();

            $table->unique(['device_token', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
