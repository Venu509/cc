<?php

namespace Domain\Project\Rules;

use Domain\Project\Models\Project;
use Illuminate\Contracts\Validation\Rule;

class ProjectExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Project::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected project does not exist.';
    }
}
