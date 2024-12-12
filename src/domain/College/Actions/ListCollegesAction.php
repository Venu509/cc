<?php

namespace Domain\College\Actions;

use App\Models\User;
use Domain\College\Resources\CollegeResources;
use Domain\User\Resources\UserProfileResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Support\Helper\Helper;

class ListCollegesAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $roles = $params['roles'] ?? null;
        $isAssignedTo = $params['isAssignedTo'] ?? false;
        $page = $params['page'] ?? null;

        return User::query()
            ->with(['userDetail'])
            ->whereHas('roles', function (Builder $builder) {
                return $builder->whereNotIn('name', ['master', 'super-admin', 'admin']);
            })
            ->when($isAssignedTo, function (Builder $builder) {
                return $builder->where('added_by', auth()->user()->id);
            })
            ->when($roles, function (Builder $builder) use ($roles) {
                return $builder->whereHas('roles', function (Builder $builder) use ($roles) {
                    return $builder->whereIn('name', $roles);
                });
            })
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function($builderBuilder) use ($search) {
                    return $builderBuilder
                        ->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('username', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%")
                        ->orWhere('alternative_number', 'LIKE', "%$search%");
                });
            })
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10, ['*'], 'page', $page)
            ->withQueryString()
            ->through(function ($user) {
                return CollegeResources::make($user);
            });
    }
}
