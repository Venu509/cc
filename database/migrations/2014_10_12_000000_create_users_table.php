<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternative_number')->nullable();
            $table->string('username')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_active')->default(true);
            $table->enum('login_via', ['phone', 'email', 'both'])->default('email');
            $table->boolean('is_profile_completed')->default(true);
            $table->boolean('is_enabled_app_notifications')->default(false);
            $table->boolean('is_enabled_push_notifications')->default(false);
            $table->integer('code')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->longText('avatar')->nullable();
            $table->jsonb('profile_completion')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users');
            $table->foreignIdFor(User::class, 'added_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
