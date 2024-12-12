<?php

use Domain\Branch\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_details', static function (Blueprint $table) {
            $table->foreignIdFor(Branch::class, 'branch_id')->after('user_id')->nullable()->constrained('branches')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_details', static function (Blueprint $table) {
            $table->dropColumn(['branch_id']);
        });
    }
};
