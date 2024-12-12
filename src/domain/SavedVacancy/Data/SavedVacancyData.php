<?php

namespace Domain\SavedVacancy\Data;

class SavedVacancyData
{
    public function __construct(
        public string $vacancyId,
        public string $role,
        public int $candidate,
    ) {
    }
}
