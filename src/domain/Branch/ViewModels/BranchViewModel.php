<?php

namespace Domain\Branch\ViewModels;

use Domain\Branch\Actions\ListBranchesAction;
use Domain\Branch\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?Branch $branch = null
    ) {
    }

    public function branches(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin', 'master'],
            'user' => Auth::user(),
        ];

        return (new ListBranchesAction())->execute($params);
    }

}
