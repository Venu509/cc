<?php

namespace Domain\Student\Rules;

use Domain\Student\Models\Student;
use Illuminate\Contracts\Validation\Rule;

class ValidateStudentIdsForTypeRule implements Rule
{
    protected string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function passes($attribute, $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        if ($this->type === 'single' && count($value) !== 1) {
            return false;
        }

        if ($this->type === 'bulk' && count($value) <= 1) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->type === 'single'
            ? 'The ids field must contain exactly one ID for single delete.'
            : 'The ids field must contain at least one ID for bulk delete.';
    }
}
