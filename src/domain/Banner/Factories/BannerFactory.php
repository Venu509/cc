<?php

namespace Domain\Banner\Factories;

use App\Models\User;
use Domain\Banner\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'image' => $this->faker->imageUrl(),
            'url' => $this->faker->name(),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
