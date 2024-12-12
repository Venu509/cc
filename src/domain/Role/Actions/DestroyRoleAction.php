<?php

namespace Domain\Role\Actions;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DestroyRoleAction
{
    public function execute(Role $role): void
    {
        DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
        DB::table('model_has_roles')->where('role_id', $role->id)->delete();

        $role->delete();
    }
}
