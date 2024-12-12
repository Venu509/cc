<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->userName(),
            'email_verified_at' => now(),
            'phone' => $this->faker->phoneNumber(),
            'password' => Hash::make('12'),
            'code' => 1010,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
            'is_active' => true,
            'modified_by' => 1,
            'added_by' => 1,
            'profile_completion' => $this->getProfileCompletion(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    protected function getProfileCompletion(): array
    {
        return [
            'personal-details' => true,
            'professional-information' => false,
            'educational-background' => false,
            'work-experiences' => false,
            'skill-and-certificates' => false,
            'resume-and-portfolio' => false,
            'job-preferences' => false,
            'additional-information' => false,
        ];
    }
}
