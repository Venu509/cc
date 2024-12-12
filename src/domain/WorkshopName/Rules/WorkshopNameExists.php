<?php

namespace Domain\WorkshopName\Rules;

use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Contracts\Validation\Rule;

class WorkshopNameExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return WorkshopName::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected workshop does not exist.';
    }
}
