<?php

namespace Domain\Notification\Factories;

use App\Models\User;
use Domain\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::first()->id,
            'user_type' => $this->faker->randomElement(['customer', 'consultant']),
            'type' => $this->faker->randomElement(['general', 'offer', 'push', 'call']),
            'domain' => $this->faker->unique()->slug,
            'title' => $this->faker->paragraph(2),
            'message' => $this->faker->paragraph(),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}