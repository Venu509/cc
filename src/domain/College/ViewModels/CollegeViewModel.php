<?php

namespace Domain\College\ViewModels;

use Domain\College\Actions\ListCollegesAction;
use Domain\College\Helpers\CollegeHelper;
use Domain\User\Actions\ListUsersAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class CollegeViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
    ) {
    }

    public function colleges(): LengthAwarePaginator
    {
        $role = auth()->user()->roles()->first()->name;
        $isAssignedTo = !in_array($role, ['admin', 'master'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'isAssignedTo' => $isAssignedTo,
            'roles' => ['government', 'institution'],
        ];

        return (new ListCollegesAction())->execute($params);
    }

    public function types(): Collection
    {
        return (new CollegeHelper())->types();
    }

    public function branches(): Collection
    {
        return (new CollegeHelper())->branches();
    }
}
