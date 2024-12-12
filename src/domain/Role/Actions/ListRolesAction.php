<?php

namespace Domain\Role\Actions;

use Domain\Role\Resources\RoleResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class ListRolesAction
{
    public function execute(
        ?array $params,
        bool   $isPagination = true
    ): Collection|LengthAwarePaginator
    {
        $search = $params['search'] ?? null;
        $ignore = $params['ignore'] ?? null;
        $roles = $params['roles'] ?? null;

        return Role::query()
            ->when($roles, function (Builder $builder) use ($roles) {
                return $builder
                    ->whereIn('name', $roles);
            })
            ->when($ignore, function (Builder $builder) use ($ignore) {
                return $builder
                    ->whereNotIn('name', $ignore);
            })
            ->when($search, function (Builder $builder) use ($search) {
                return $builder
                    ->where('name', 'LIKE', "%$search%")
                    ->orWhere('display_name', 'LIKE', "%$search%");
            })
            ->latest('created_at')
            ->when(!$isPagination, function (Builder $builder) {
                return $builder->select(
                    'display_name as label',
                    'name as slug',
                    'id as value'
                )->get();
            })
            ->when($isPagination, function (Builder $builder) {
                return $builder->paginate($params['limit'] ?? 10)
                    ->withQueryString()
                    ->through(function ($role) {
                        return RoleResources::make($role);
                    });
            });
    }
}
