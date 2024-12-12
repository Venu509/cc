<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('notifications', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users');
            $table->string('user_type')->default('supervisor');
            $table->string('domain')->nullable();
            $table->enum('type', ['push', 'call', 'general'])->default('push');
            $table->text('title')->nullable();
            $table->longText('message')->nullable();
            $table->jsonb('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users');
            $table->foreignIdFor(User::class, 'added_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
