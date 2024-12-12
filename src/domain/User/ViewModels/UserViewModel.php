<?php

namespace Domain\User\ViewModels;

use App\Models\User;
use Spatie\ViewModels\ViewModel;
use Domain\User\Actions\ListUsersAction;
use Domain\User\Resources\UserProfileResources;
use Illuminate\Pagination\LengthAwarePaginator;

class UserViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ?User $user = null
    ) {
    }

    public function employees(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'role' => request()->has('role') ? request()->get('role') : null,
        ];

        return (new ListUsersAction())->execute($params);
    }

    public function employee(): array|UserProfileResources
    {
        if ($this->user) {
            return UserProfileResources::make(
                $this->user
            );
        }

        return [];
    }
}
