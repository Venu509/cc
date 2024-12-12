<?php

namespace Domain\Vacancy\Rules;

use Domain\Vacancy\Models\Vacancy;
use Illuminate\Contracts\Validation\Rule;

class VacancyExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Vacancy::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected vacancy does not exist.';
    }
}
