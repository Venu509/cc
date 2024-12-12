<?php

namespace Domain\API\Authentication\Factories;

use Domain\API\Authentication\Models\InfoUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfoUserFactory extends Factory
{
    protected $model = InfoUser::class;

    public function definition(): array
    {
        return [
            'full_name' => $this->faker->firstName().' '.$this->faker->firstName(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'age' => $this->faker->numberBetween(10, 50),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'dob' => $this->faker->date(),
            'location' => collect([
                'latitude' => 123345.5446,
                'longitude' => 0977654.5446,
            ]),
            'height' => $this->faker->numberBetween(10, 200),
            'weight' => $this->faker->numberBetween(10, 200),
            'city' => $this->faker->city(),
            'state' => $this->faker->streetAddress(),
            'country' => $this->faker->country(),
            'charge' => $this->faker->numberBetween(0, 200),
            'year_of_experience' => $this->faker->numberBetween(0, 10),
            'degree' => $this->faker->imageUrl(),
            'avatar' => $this->faker->imageUrl(),
            'user_id' => 1,
        ];
    }
}
