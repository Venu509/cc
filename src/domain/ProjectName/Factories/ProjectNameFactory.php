<?php

namespace Domain\ProjectName\Factories;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectNameFactory extends Factory
{
    protected $model = ProjectName::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
