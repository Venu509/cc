<?php

namespace Domain\Vacancy\Data;

class VacancyData
{
    public function __construct(
        public ?string $id,
        public string $title,
        public int $company,
        public string $salary,
        public string $yearsOfExperiences,
        public ?array  $parent,
        public ?array $child,
        public string $description,
        public ?array $qualifications,
        public ?array $benefits,
        public ?array $workModes,
        public string $noOfOpenings,
        public array $locations,
        public string $expireDate,
        public ?array $keySkills,
        public ?bool $hasAdditionalQuestions,
        public ?array $additionalQuestions,
        public ?bool $isInternalApplication,
        public ?string $externalLink,
        public string $salaryFrequency,
        public bool $isWalkInInterview,
        public ?string $startDate,
        public ?string $endDate,
    ) {
    }
}
