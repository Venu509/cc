<?php

namespace Domain\Branch\Factories;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph('2'),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
