<?php

namespace Domain\ProjectName\Actions;

use Domain\ProjectName\Models\ProjectName;
use Domain\ProjectName\Resources\ProjectNameResources;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProjectsNamesAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $isFiltered = $params['isFiltered'] ?? false;
        $filterRestrictRoles = $params['filterRestrictRoles'] ?? null;
        $user = $params['user'] ?? null;


        return ProjectName::query()
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function (Builder $builder) use ($search) {
                    return $builder->where('name', 'LIKE', "%$search%")
                        ->orWhere('semester', 'LIKE', "%$search%")
                        ->orWhere(function (Builder $builder) use ($search) {
                            return $builder->whereHas('branch', function ($query) use ($search) {
                                $query->where('name', 'LIKE', "%$search%");
                            });
                        });
                });
            })
            ->when($isFiltered && $user->role !== $filterRestrictRoles, function (Builder $builder) use ($user) {
                return $builder->where('added_by', $user->id);
            })
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($projectsName) {
                return ProjectNameResources::make($projectsName);
            });
    }
}
