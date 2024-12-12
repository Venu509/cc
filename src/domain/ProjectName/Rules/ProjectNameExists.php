<?php

namespace Domain\ProjectName\Rules;

use Domain\ProjectName\Models\ProjectName;
use Illuminate\Contracts\Validation\Rule;

class ProjectNameExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return ProjectName::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected project name does not exist.';
    }
}
