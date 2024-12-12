<?php

namespace Domain\Job\Enums;

enum ApplicantTracking
{
    public const APPLIED = 'applied';
    public const VIEWED = 'viewed';
    public const SHORTLISTED = 'shortlisted';
    public const REJECTED = 'rejected';

    public static function getValues(): array
    {
        return [
            self::APPLIED,
            self::VIEWED,
            self::SHORTLISTED,
            self::REJECTED,
        ];
    }
}
