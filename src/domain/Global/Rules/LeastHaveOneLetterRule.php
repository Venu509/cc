<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class LeastHaveOneLetterRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return preg_match('/[a-zA-Z]/', $value) && preg_match('/^[a-zA-Z\s]+$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must contain at least one letter.';
    }
}
