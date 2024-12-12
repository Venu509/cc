<?php

namespace Domain\Candidate\ViewModels;

use App\Models\User;
use Domain\Candidate\Helpers\CandidateHelper;
use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Support\Collection;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\ViewModels\ViewModel;

class CandidateCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?User $oldUser = null
    )
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws JsonException
     */
    public function candidate(): array
    {
        return (new CandidateHelper())->data($this->oldUser);
    }
    public function noticePeriods(): Collection
    {
        return (new EmployeeHelper())->noticePeriods();
    }
    public function noOfExperiences(): Collection
    {
        return (new EmployeeHelper())->noOfExperiences();
    }
    public function qualifications(): Collection
    {
        return (new EmployeeHelper())->qualifications();
    }
    public function countries(): Collection
    {
        return (new EmployeeHelper())->countries();
    }
    public function keySkills(): Collection
    {
        return (new EmployeeHelper())->keySkills();
    }

    public function userPreferredLocations(): Collection
    {
        return (new EmployeeHelper())->userPreferredLocations();
    }

    public function jobTypes(): Collection
    {
        return (new EmployeeHelper())->workModes();
    }

    public function industries(): Collection
    {
        return (new EmployeeHelper())->industries();
    }

    public function employmentStatus(): Collection
    {
        return (new EmployeeHelper())->employmentStatus();
    }
}
