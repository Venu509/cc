<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class AcceptTextAndNumbersRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return preg_match('/^[a-zA-Z0-9\s]+$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute may only contain letters,and numbers. Special characters are not allowed.';
    }
}
