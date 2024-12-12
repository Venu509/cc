<?php

namespace Domain\User\Enums;

enum ProfileCompletion: string
{
    case STEP_ONE = 'personal-details';
    case STEP_TWO = 'professional-information';
    case STEP_THREE = 'educational-background';
    case STEP_FOUR = 'work-experiences';
    case STEP_FIVE = 'skill-and-certificates';
    case STEP_SIX = 'resume-and-portfolio';
    case STEP_SEVEN = 'job-preferences';
    case STEP_EIGHT = 'additional-information';

    public static function getAllSteps(): array
    {
        return array_map(static fn($case) => $case->value, self::cases());
    }
}
