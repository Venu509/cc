<?php

namespace Domain\SavedVacancy\Actions;

use Domain\SavedVacancy\Models\SavedVacancy;
use Domain\SavedVacancy\Resources\SavedVacancyResources;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSavedVacanciesAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $isFiltered = $params['isFiltered'] ?? false;
        $filterRestrictRoles = $params['filterRestrictRoles'] ?? null;
        $user = $params['user'] ?? null;

        return SavedVacancy::query()
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
                return SavedVacancyResources::make($branch);
            });
    }
}
