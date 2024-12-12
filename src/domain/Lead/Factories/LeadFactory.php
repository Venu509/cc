<?php

namespace Domain\Lead\Factories;

use App\Models\User;
use Domain\Lead\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'slug' => $this->faker->unique()->slug(),
            'type' => $this->faker->randomElement(['government', 'institution', 'candidate', 'company']),
            'status' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'user_id' => User::first()->id,
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
