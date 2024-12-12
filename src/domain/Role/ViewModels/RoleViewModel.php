<?php

namespace Domain\Role\ViewModels;

use Spatie\ViewModels\ViewModel;
use Domain\Role\Actions\ListRolesAction;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ListRolesAction $listRolesAction
    ) {
    }

    public function roles(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
        ];

        return $this->listRolesAction->execute($params);
    }
}
