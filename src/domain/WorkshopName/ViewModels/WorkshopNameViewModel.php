<?php

namespace Domain\WorkshopName\ViewModels;

use Domain\WorkshopName\Actions\ListWorkshopsNamesAction;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkshopNameViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?WorkshopName $workshop = null
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

        return (new ListWorkshopsNamesAction())->execute($params);
    }

}
