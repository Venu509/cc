<?php

namespace Domain\Role\Seeders;

use Support\Helper\Helper;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    use Helper;

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        collect(roleAbilities())->each(function ($ability, $roleSlug) {
            $createdRole = Role::create([
                'name' => $roleSlug,
                'display_name' => getStringFromSlug($roleSlug),
            ]);

            $abilities = [];
            foreach ($ability['domains'] as $domain => $permissions) {
                if (is_array($permissions)) {
                    foreach ($permissions as $permission) {
                        $abilities[] = $permission . '-' . $domain;
                    }
                }
            }

            $createdRole->givePermissionTo($abilities);
        });
    }
}
