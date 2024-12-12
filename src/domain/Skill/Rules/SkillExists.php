<?php

namespace Domain\Skill\Rules;

use Domain\Skill\Models\Skill;
use Illuminate\Contracts\Validation\Rule;

class SkillExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Skill::query()->where($attribute, $value)->exists();
    }

    public function message(): string
    {
        return 'The selected skill does not exist.';
    }
}
