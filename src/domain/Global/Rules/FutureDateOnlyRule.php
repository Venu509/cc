<?php

namespace Domain\Global\Rules;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class FutureDateOnlyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            $date = Carbon::parse($value)->startOfDay();

            $minDate = Carbon::now()->startOfDay();
            $maxDate = Carbon::now()->addYears(1)->endOfDay();

            return !$date->lt($minDate) && !$date->gt($maxDate);
        } catch (Exception $e) {
            return false;
        }
    }

    public function message(): string
    {
        return 'The :attribute must be a date within 1 year.';
    }
}
