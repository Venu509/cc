<?php

namespace Domain\Company\ViewModels;

use Domain\Company\Actions\ListCompaniesAction;
use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class CompanyViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
    ) {
    }

    public function companies(): LengthAwarePaginator
    {
        $role = auth()->user()->roles()->first()->name;
        $isAssignedTo = !in_array($role, ['admin', 'master'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'isAssignedTo' => $isAssignedTo,
            'roles' => ['company'],
        ];

        return (new ListCompaniesAction())->execute($params);
    }

    public function noticePeriods(): Collection
    {
        return (new EmployeeHelper())->noticePeriods();
    }

    public function keySkills(): Collection
    {
        return (new EmployeeHelper())->keySkills();
    }
}
