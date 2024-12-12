<?php

namespace Domain\Resume\ViewModels;

use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class ResumeViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
    ) {
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

    public function jobTypes(): Collection
    {
        return (new EmployeeHelper())->workModes();
    }
}
