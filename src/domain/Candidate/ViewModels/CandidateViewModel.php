<?php

namespace Domain\Candidate\ViewModels;

use Domain\Global\Helpers\EmployeeHelper;
use Domain\User\Actions\ListCandidatesAction;
use Domain\User\Actions\ListUsersAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class CandidateViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
    ) {
    }

    public function candidates(): LengthAwarePaginator
    {
        $role = getUserRole(auth()->user());
        $isAssignedTo = !in_array($role, ['admin', 'master'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'notice-period' => request()->has('notice-period') ? request()->get('notice-period') : null,
            'isAssignedTo' => $isAssignedTo,
            'roles' => ['candidate'],
        ];

        return (new ListCandidatesAction())->execute($params);
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
