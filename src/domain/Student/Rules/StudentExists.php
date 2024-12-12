<?php

namespace Domain\Student\Rules;

use Domain\Student\Models\Student;
use Illuminate\Contracts\Validation\Rule;

class StudentExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Student::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected student does not exist.';
    }
}
