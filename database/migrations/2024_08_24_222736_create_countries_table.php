<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('code', 4)->unique(); // AF, AL, DZ, etc.
            $table->string('name');
            $table->string('region');
            $table->json('timezones'); // Store timezones as JSON
            $table->string('iso_alpha_2', 2);
            $table->string('iso_alpha_3', 3);
            $table->string('iso_numeric', 3);
            $table->string('phone'); // Single string, can be modified for array storage if needed
            $table->string('emoji');
            $table->string('image');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
