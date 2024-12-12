<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfExperiencesArrayContainAtLeastOneExperience implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        return count(array_filter($value, static function ($item) {
                return (!empty($item['companyName'])) ||
                    (!empty($item['jobTitle'])) ||
                    (!empty($item['startDate'])) ||
                    (!empty($item['endDate'])) ||
                    (!empty($item['responsibilities'])) ||
                    (!empty($item['achievements']));
            })) > 0;
    }

    public function message(): string
    {
        return 'At least one experience must be include.';
    }
}