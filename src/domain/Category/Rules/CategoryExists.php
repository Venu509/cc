<?php

namespace Domain\Category\Rules;

use Domain\Category\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class CategoryExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Category::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected category does not exist.';
    }
}
