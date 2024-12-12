<?php

namespace Domain\Workshop\ViewModels;

use Domain\Workshop\Actions\ListWorkshopsAction;
use Domain\Workshop\Models\Workshop;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkshopViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?Workshop $workshop = null
    ) {
    }

    public function workshops(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin'],
            'user' => Auth::user(),
        ];

        return (new ListWorkshopsAction())->execute($params);
    }

}
