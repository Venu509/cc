<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_details', static function (Blueprint $table) {
            $table->string('gst')->after('years_of_existence')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_details', static function (Blueprint $table) {
            $table->dropColumn(['gst']);
        });
    }
};
