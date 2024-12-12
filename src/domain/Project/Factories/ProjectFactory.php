<?php

namespace Domain\Project\Factories;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\Project\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'type' => 'mini',
            'branch_id' => Branch::first()->id,
            'semester' => $this->faker->paragraph('2'),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
