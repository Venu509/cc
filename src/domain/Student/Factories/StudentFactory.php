<?php

namespace Domain\Student\Factories;

use App\Models\User;
use Domain\Student\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->city(),
            'first_name' => $this->faker->paragraph('2'),
            'last_name' => $this->faker->paragraph('2'),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
        ];
    }
}
