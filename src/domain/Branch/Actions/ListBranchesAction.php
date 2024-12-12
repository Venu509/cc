<?php

namespace Domain\Branch\Actions;

use Domain\Branch\Models\Branch;
use Domain\Branch\Resources\BranchResources;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListBranchesAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $isFiltered = $params['isFiltered'] ?? false;
        $filterRestrictRoles = $params['filterRestrictRoles'] ?? null;
        $user = $params['user'] ?? null;

        return Branch::query()
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function($queryBuilder) use ($search) {
                    return $queryBuilder
                        ->where('name', 'LIKE', "%$search%");
                });
            })
            ->when($isFiltered && $user->role !== $filterRestrictRoles, function (Builder $builder) use ($user) {
                return $builder->where('added_by', $user->id);
            })
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($branch) {
                return BranchResources::make($branch);
            });
    }
}
