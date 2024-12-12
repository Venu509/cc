<?php

namespace Domain\Job\ViewModels;

use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class JobViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
    ) {
    }

    public function jobTypes(): Collection
    {
        return (new EmployeeHelper())->workModes();
    }
}
