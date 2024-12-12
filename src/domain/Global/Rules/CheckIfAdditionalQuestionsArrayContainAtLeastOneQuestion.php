<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfAdditionalQuestionsArrayContainAtLeastOneQuestion implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (request('hasAdditionalQuestions') !== 'yes') {
            return true;
        }

        if (!is_array($value)) {
            return false;
        }

        return count(array_filter($value, static function ($item) {
                return (!empty($item['question'])) ||
                    (!empty($item['answers']));
            })) > 0;
    }

    public function message(): string
    {
        return 'At least one question must be include.';
    }
}