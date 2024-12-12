<?php

namespace Domain\Skill\Factories;

use App\Models\User;
use Domain\Skill\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->city(),
//            'slug' => $this->faker->unique()->slug(),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
