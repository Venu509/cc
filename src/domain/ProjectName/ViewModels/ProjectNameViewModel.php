<?php

namespace Domain\ProjectName\ViewModels;

use Domain\ProjectName\Actions\ListProjectsNamesAction;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectNameViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?ProjectName $workshop = null
    ) {
    }

    public function projectsNames(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin'],
            'user' => Auth::user(),
        ];

        return (new ListProjectsNamesAction())->execute($params);
    }

}
