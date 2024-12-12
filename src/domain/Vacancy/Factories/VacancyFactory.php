<?php

namespace Domain\Vacancy\Factories;

use App\Models\User;
use Domain\Category\Models\Category;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    /**
     * @throws \JsonException
     */
    public function definition(): array
    {
        return [
            'company_id' => array_rand(array_flip(
                User::query()
                    ->whereHas('roles', function (Builder $builder) {
                        $builder->where('name', 'company');
                    })->get()->pluck('id')->toArray()
            )),
            'title' => $this->faker->jobTitle(),
            'salary' => $this->faker->randomFloat(2, 30000, 150000),
            'description' => $this->faker->paragraphs(3, true),
            'qualifications' => json_encode([
                'B.sc',
                'B.tech',
                'B.E'
            ], JSON_THROW_ON_ERROR | true),
            'benefits' => json_encode([
                'Health insurance'
            ], JSON_THROW_ON_ERROR | true),
            'locations' => json_encode([
                $this->faker->city(),
            ], JSON_THROW_ON_ERROR | true),
            'work_modes' => json_encode([
                'Freelancing',
                'Contract'
            ], JSON_THROW_ON_ERROR | true),
            'no_of_openings' => $this->faker->numberBetween(1, 10),
            'application_method' => $applicationMethod = $this->faker->randomElement(['internal', 'external']),
            'external_link' => $applicationMethod === 'external' ? $this->faker->url() : null,
            'expire_date' => $this->faker->date(),
            'modified_by' => User::first()->id,
            'added_by' => User::first()->id,
            'category_id' =>  array_rand(array_flip(
                Category::query()
                    ->whereNotNull('parent_id')
                    ->get()
                    ->pluck('id')
                    ->toArray()
            )),
            'salary_frequency' => 'per-annum',
        ];
    }
}
