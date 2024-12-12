<?php

namespace Domain\PageSetting\Factories;

use App\Models\User;
use Domain\PageSetting\Models\PageSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageSettingFactory extends Factory
{
    protected $model = PageSetting::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['about', 'contact']),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->unique()->phoneNumber(),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
