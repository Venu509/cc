<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class TextOnly implements Rule
{
    public function passes($attribute, $value): bool
    {
        return preg_match('/^[a-zA-Z\s]+$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute may only contain letters and can not use special characters or numbers.';
    }
}
