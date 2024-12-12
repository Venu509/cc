<?php

namespace Domain\Application\ViewModels;

use Domain\Vacancy\Actions\ListVacanciesAction;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class ApplicationViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
    ) {
    }

    public function vacancies(): LengthAwarePaginator
    {
        $role = auth()->user()->roles()->first()->name;
        $isBelongsToMe = !in_array($role, ['admin', 'master', 'super-admin'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'tab' => request()->has('tab') ? request()->get('tab') : 'pending',
            'isApplied' => !request()->has('is-applied') || request()->get('is-applied') === 'yes',
            'isBelongsToMe' => $isBelongsToMe,
        ];

        $statusMap = [
            'pending' => ['applied', 'viewed'],
            'shortlisted' => ['shortlisted'],
            'rejected' => ['rejected'],
        ];

        $params['tab'] = $statusMap[$params['tab']] ?? [];

        return (new ListVacanciesAction())->execute($params);
    }

}
