<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class MultipleDropdownValidate implements Rule
{
    protected ?array $allowedValues;
    protected ?Rule $existsRule;

    public function __construct(?array $allowedValues = null, ?Rule $existsRule = null)
    {
        $this->allowedValues = $allowedValues;
        $this->existsRule = $existsRule;
    }

    public function passes($attribute, $value): bool
    {
        if (!is_array($value) || !isset($value['value'])) {
            return false;
        }

        if ($this->existsRule && !$this->existsRule->passes($attribute, $value['value'])) {
            return false;
        }

        if ($this->allowedValues !== null && !in_array($value['value'], $this->allowedValues, true)) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'The :attribute must contain valid input.';
    }
}