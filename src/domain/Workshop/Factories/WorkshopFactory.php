<?php

namespace Domain\Workshop\Factories;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\Workshop\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkshopFactory extends Factory
{
    protected $model = Workshop::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'branch_id' => Branch::first()->id,
            'semester' => $this->faker->paragraph('2'),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
