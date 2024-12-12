<?php

namespace Domain\Workshop\Actions;

use Domain\Workshop\Models\Workshop;
use Domain\Workshop\Resources\WorkshopResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Support\Helper\Helper;

class ListWorkshopsAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator
    {
        $search = $params['search'] ?? null;
        $isFiltered = $params['isFiltered'] ?? false;
        $filterRestrictRoles = $params['filterRestrictRoles'] ?? null;
        $user = $params['user'] ?? null;

        return Workshop::query()
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function (Builder $builder) use ($search) {
                    return $builder->whereHas('workshopName', function ($query) use ($search) {
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
            ->when($isFiltered && $user->role !== $filterRestrictRoles, function (Builder $builder) use ($user) {
                return $builder->where('added_by', $user->id);
            })
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($Workshop) {
                return WorkshopResources::make($Workshop);
            });
    }
}
