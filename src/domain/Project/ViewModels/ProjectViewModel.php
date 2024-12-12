<?php

namespace Domain\Project\ViewModels;

use Domain\Project\Actions\ListProjectsAction;
use Domain\Project\Models\Project;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?Project $project = null
    ) {
    }

    public function projects(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin'],
            'user' => Auth::user(),
        ];

        return (new ListProjectsAction())->execute($params);
    }

}
