<?php

namespace Domain\Branch\Rules;

use Domain\Branch\Models\Branch;
use Illuminate\Contracts\Validation\Rule;

class BranchExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Branch::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected branch does not exist.';
    }
}
