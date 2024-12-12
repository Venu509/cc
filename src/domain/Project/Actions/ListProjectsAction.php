<?php

namespace Domain\Project\Actions;

use Domain\Project\Models\Project;
use Domain\Project\Resources\ProjectResources;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProjectsAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $type = $params['type'] ?? null;
        $isFiltered = $params['isFiltered'] ?? false;
        $filterRestrictRoles = $params['filterRestrictRoles'] ?? null;
        $user = $params['user'] ?? null;

        return Project::query()
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function (Builder $builder) use ($search) {
                    return $builder->whereHas('projectName', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        })
                        ->orWhere('semester', 'LIKE', "%$search%")
                        ->orWhere(function (Builder $builder) use ($search) {
                            return $builder->whereHas('branch', function ($query) use ($search) {
                                $query->where('name', 'LIKE', "%$search%");
                            });
                        });
                });
            })
            ->when($type, function (Builder $builder) use ($type) {
                return $builder->where('type', $type);
            })
            ->when($isFiltered && $user->role !== $filterRestrictRoles, function (Builder $builder) use ($user) {
                return $builder->where('added_by', $user->id);
            })
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($project) {
                return ProjectResources::make($project);
            });
    }
}
