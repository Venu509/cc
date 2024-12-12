<?php

namespace Domain\Global\Rules;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class PastDateOnlyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            $date = Carbon::parse($value);

            $minDate = Carbon::now()->subYears(100);
            $maxDate = Carbon::yesterday()->endOfDay();

            if ($date->lt($minDate) || $date->gt($maxDate)) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function message(): string
    {
        return 'The :attribute must be a valid past date.';
    }
}
