<?php

namespace Domain\Role\Actions;

use Domain\Role\Data\RoleData;
use Spatie\Permission\Models\Role;

class StoreRoleAction
{
    public function execute(
        RoleData $roleData,
        Role $role = new Role()
    ): Role {
        $role->forceFill([
            'name' => slugGenerator($roleData->name),
            'display_name' => $roleData->name,
        ])->save();

        $role->refresh();

        return $role;
    }
}
