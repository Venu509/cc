<?php

namespace Domain\SavedVacancy\ViewModels;

use Domain\Global\Helpers\EmployeeHelper;
use Domain\SavedVacancy\Actions\ListSavedVacanciesAction;
use Domain\Vacancy\Actions\ListVacanciesAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;

class SavedVacancyViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
    ) {
    }

    public function savedVacancies(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin'],
            'isSavedJobs' => true,
        ];

        return (new ListVacanciesAction())->execute($params);
    }

}
