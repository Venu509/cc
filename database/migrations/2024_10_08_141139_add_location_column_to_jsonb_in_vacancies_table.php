<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('vacancies')->update(['location' => null]);

        Schema::table('vacancies', static function (Blueprint $table) {
            $table->jsonb('location')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vacancies', static function (Blueprint $table) {
            $table->string('location', 120)->nullable()->change();
        });
    }
};
