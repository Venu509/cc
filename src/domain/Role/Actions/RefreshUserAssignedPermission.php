<?php

namespace Domain\Role\Actions;

use Domain\Role\Data\RoleData;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RefreshUserAssignedPermission
{
    public function execute(
        RoleData $roleData,
        Role $role = new Role()
    ): void {
        $users = Role::query()->find($role->id)->customers->pluck('id');

        DB::table('model_has_permissions')
            ->whereIn(
                'model_id',
                $users->toArray()
            )->delete();

        foreach ($users as $user) {
            foreach ($roleData->permissions['permissions'] as $permission) {
                DB::table('model_has_permissions')->insert([
                    'model_id' => $user,
                    'permission_id' => $permission['id'],
                ]);
            }
        }
    }
}
