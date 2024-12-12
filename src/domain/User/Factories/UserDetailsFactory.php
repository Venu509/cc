<?php

namespace Domain\User\Factories;
use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\User\Models\UserDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailsFactory extends Factory
{
    protected $model = UserDetails::class;

    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'student_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'address' => $this->faker->address,
            'age' => $this->faker->numberBetween(18, 60),
            'dob' => $this->faker->date,
            'pan_card_number' => $this->faker->unique()->bothify('PAN-####'),
            'adhar_card_number' => $this->faker->unique()->bothify('AADHAR-##########'),
            'no_of_experiences' => $this->faker->randomElement([
                'less-than-1-year', '1-3-years', '3-5-years', '5-years', '10-years', '10+-years'
            ]),
            'years_of_existence' => $this->faker->numberBetween(1, 100),
            'qualification' => $this->faker->randomElement(['high-school', 'diploma', 'bachelors-degree', 'masters-degree']),
            'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'resume' => $this->faker->text,
            'experience' => $this->faker->sentence,
            'skill_set' => $this->faker->words(3, true),
            'company_name' => $this->faker->company,
            'company_mobile_number' => $this->faker->phoneNumber,
            'company_email' => $this->faker->unique()->safeEmail,
            'contact_person' => $this->faker->name,
            'contact_person_email' => $this->faker->unique()->safeEmail,
            'contact_person_phone' => $this->faker->phoneNumber,
            'contact_person_address' => $this->faker->address,
            'date_of_register' => $this->faker->date(),
            'company_url' => $this->faker->url,
            'company_logo' => $this->faker->imageUrl,
            'registration_doc' => $this->faker->text,

            'current_job_title' => $this->faker->name(),
            'current_company' => $this->faker->name(),
            'current_salary' => $this->faker->numberBetween(1, 100),
            'expected_salary' => $this->faker->numberBetween(1, 100),
            'notice_period' => $this->faker->randomElement([
                'immediate', '15-days', '30-days', '60-days', '90-days', 'more'
            ]),

            'registered_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::first()->id,
            'branch_id' => null,
        ];
    }
}