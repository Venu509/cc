<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->longText('image');
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users');
            $table->foreignIdFor(User::class, 'added_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
