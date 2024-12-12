<?php

namespace Domain\Country\Factories;

use Domain\Country\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use JsonException;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    /**
     * @throws JsonException
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->countryCode(),
            'name' => $this->faker->country(),
            'region' => $this->faker->randomElement(['Asia', 'Europe', 'Africa', 'Oceania', 'Americas']),
            'timezones' => json_encode([$this->faker->timezone() => $this->faker->randomElement(['+01:00', '+02:00', '+03:00', '-01:00', '-05:00'])], JSON_THROW_ON_ERROR),
            'iso_alpha_2' => $this->faker->countryCode(),
            'iso_alpha_3' => $this->generateAlpha3Code($this->faker->countryCode()),
            'iso_numeric' => str_pad($this->faker->randomNumber(3), 3, '0', STR_PAD_LEFT),
            'phone' => $this->faker->e164PhoneNumber(),
            'emoji' => '🇦🇫', // You might want to replace this with dynamic values
            'image' => $this->faker->imageUrl(640, 480, 'flags'),
        ];
    }

    private function generateAlpha3Code(string $alpha2Code): string
    {
        $alpha3Map = [
            'AF' => 'AFG',
            'AL' => 'ALB',
            'DZ' => 'DZA',
            'AS' => 'ASM',
        ];

        return $alpha3Map[$alpha2Code] ?? 'UNKNOWN';
    }
}
