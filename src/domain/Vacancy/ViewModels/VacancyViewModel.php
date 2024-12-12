<?php

namespace Domain\Vacancy\ViewModels;

use Domain\Vacancy\Actions\ListVacanciesAction;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;

class VacancyViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?Vacancy $project = null
    ) {
    }

    public function vacancies(): LengthAwarePaginator
    {
        $role = auth()->user()->roles()->first()->name;
        $isBelongsToMe = !in_array($role, ['admin', 'master', 'super-admin'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isBelongsToMe' => $isBelongsToMe,
        ];

        return (new ListVacanciesAction())->execute($params);
    }

}
