<?php

use App\Models\User;
use Domain\Branch\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('student_id')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->longText('resume')->nullable();
            $table->longText('pan_number')->nullable();
            $table->string('aadhaar_number')->nullable();
            $table->longText('qualification')->nullable();
            $table->enum('gender',['male', 'female', 'other'])->default('male')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
            $table->longText('image')->nullable();
            $table->foreignIdFor(User::class, 'modified_by')->constrained('users');
            $table->foreignIdFor(Branch::class, 'branch_id')->constrained('branches');
            $table->foreignIdFor(User::class, 'added_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
