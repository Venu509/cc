<?php

namespace Domain\Job\ViewModels;

use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Resources\VacancyResources;
use Spatie\ViewModels\ViewModel;

class JobShowViewModel extends ViewModel
{
    public function __construct(
        public ?Vacancy $vacancy = null
    )
    {
    }

    public function vacancy(): VacancyResources
    {
        return VacancyResources::make($this->vacancy);
    }
}
