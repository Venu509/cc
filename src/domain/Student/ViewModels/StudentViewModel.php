<?php

namespace Domain\Student\ViewModels;

use Domain\Student\Actions\ListStudentsAction;
use Domain\Student\Models\Student;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?Student $branch = null
    ) {
    }

    public function students(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin'],
            'user' => Auth::user(),
        ];

        return (new ListStudentsAction())->execute($params);
    }

}
