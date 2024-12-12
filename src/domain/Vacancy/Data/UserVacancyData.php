<?php

namespace Domain\Vacancy\Data;

class UserVacancyData
{
    public function __construct(
        public string $vacancyId,
        public string $role,
        public int $candidate,
        public ?array $answers,
    ) {
    }
}
