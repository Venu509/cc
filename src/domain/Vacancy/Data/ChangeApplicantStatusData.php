<?php

namespace Domain\Vacancy\Data;

class ChangeApplicantStatusData
{
    public function __construct(
        public string $vacancy,
        public string $resume,
        public bool $isAxiosRequest,
        public string $status,
        public ?string $intendedRoute,
        public ?string $tab = 'pending',
    ) {
    }
}
