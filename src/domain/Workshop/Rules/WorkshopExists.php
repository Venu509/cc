<?php

namespace Domain\Workshop\Rules;

use Domain\Workshop\Models\Workshop;
use Illuminate\Contracts\Validation\Rule;

class WorkshopExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Workshop::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected workshop does not exist.';
    }
}
