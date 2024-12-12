<?php

namespace Domain\Role\Actions;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class ListPermissionsAction
{
    public function execute(): Collection
    {
        return Permission::query()
            ->latest('created_at')
            ->get()
            ->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'domain' => $permission->domain,
                    'displayName' => $permission->display_name,
                    'guardName' => $permission->guard_name,
                ];
            })->sortBy(function ($item) {
                return $item['id'];
            })->groupBy(function ($item) {
                return $item['domain'];
            });
    }
}
