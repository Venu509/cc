<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class PanCardRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $panRegex = '/^[a-z]{5}[0-9]{4}[a-z]$/i';

        return preg_match($panRegex, $value);
    }

    public function message(): string
    {
        return 'The PAN number must be a valid PAN number.';
    }
}
