<?php

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignIdFor(WorkshopName::class, 'name')->constrained('workshops_names');
            $table->string('semester');
            $table->longText('description')->nullable();
            $table->date('date');
            $table->longText('end_date');
            $table->foreignIdFor(Branch::class, 'branch_id')->constrained('branches');
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users');
            $table->foreignIdFor(User::class, 'added_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
