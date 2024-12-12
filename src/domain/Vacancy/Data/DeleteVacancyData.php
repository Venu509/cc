<?php

namespace Domain\Vacancy\Data;

class DeleteVacancyData
{
    public function __construct(
        public string $vacancy,
        public int $user,
        public string $role,
    ) {
    }
}
