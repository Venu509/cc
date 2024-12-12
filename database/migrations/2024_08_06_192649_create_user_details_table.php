<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_details', static function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('full_name')->nullable();
            $table->string('student_id')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->mediumText('address')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('age')->nullable();
            $table->string('dob')->nullable();
            $table->string('pan_card_number')->nullable();
            $table->string('adhar_card_number')->nullable();
            $table->integer('years_of_existence')->default(0);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->longText('resume')->nullable();
            $table->longText('experience')->nullable();
            $table->longText('skill_set')->nullable();

            $table->string('company_name')->nullable();
            $table->string('company_mobile_number')->nullable();
            $table->string('company_email')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_address')->nullable();
            $table->date('date_of_register')->nullable();
            $table->string('company_url')->nullable();
            $table->longText('company_logo')->nullable();
            $table->longText('registration_doc')->nullable();

            $table->longText('current_job_title')->nullable();
            $table->longText('current_company')->nullable();
            $table->enum('no_of_experiences', ['no-experience', 'less-than-1-year', '1-3-years', '3-5-years', '5-years', '10-years', '10+-years'])->nullable();
            $table->string('current_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->enum('notice_period', ['immediate', '15-days', '30-days', '60-days', '90-days', 'more'])->nullable();
            $table->boolean('can_relocated')->default(false);

            $table->enum('qualification', ['high-school', 'diploma', 'bachelors-degree', 'masters-degree'])->nullable();
            $table->string('specialized_in')->nullable();
            $table->string('university')->nullable();
            $table->year('year_of_graduation')->nullable();
            $table->text('additional_qualification')->nullable();

            $table->text('certifications')->nullable();
            $table->text('known_languages')->nullable();

            $table->longText('portfolio')->nullable();
            $table->enum('cover_letter_type', ['file', 'text'])->default('text');
            $table->longText('cover_letter')->nullable();
            $table->longText('cover_letter_file')->nullable();

            $table->jsonb('job_preferences')->nullable();

            $table->longText('career_objective')->nullable();
            $table->longText('awards_and_recognitions')->nullable();

            $table->foreignIdFor(User::class, 'user_id')->constrained('users');
            $table->timestamp('registered_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
