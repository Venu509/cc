<?php

namespace Domain\Role\Actions;

use Domain\Role\Data\RoleData;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SyncRoleWithPermissionsAction
{
    public function execute(
        RoleData $roleData,
        Role $role = new Role()
    ): void {
        $role->syncPermissions($roleData->permissions);
//        DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
//
//        foreach (['permissions'] as $permission) {
//            DB::table('role_has_permissions')->insert([
//                'role_id' => $role->id,
//                'permission_id' => $permission['id'],
//            ]);
//        }
    }
}
