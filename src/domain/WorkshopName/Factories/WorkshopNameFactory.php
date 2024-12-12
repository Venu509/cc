<?php

namespace Domain\WorkshopName\Factories;

use App\Models\User;
use Domain\Branch\Models\Branch;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkshopNameFactory extends Factory
{
    protected $model = WorkshopName::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
