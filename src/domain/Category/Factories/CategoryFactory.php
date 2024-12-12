<?php

namespace Domain\Category\Factories;

use App\Models\User;
use Domain\Category\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->city(),
            'slug' => $this->faker->slug(),
            'parent_id' => null,
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
