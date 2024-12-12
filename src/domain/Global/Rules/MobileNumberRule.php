<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileNumberRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return preg_match('/^\d{10,12}$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid phone number with 10 to 12 digits.';
    }
}
