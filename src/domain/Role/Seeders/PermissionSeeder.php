<?php

namespace Domain\Role\Seeders;

use Illuminate\Database\Seeder;
use Support\Helper\Helper;

class PermissionSeeder extends Seeder
{
    use Helper;

    public function run(): void
    {
        foreach (roleAbilities() as $role => $roleConfig) {
            foreach ($roleConfig['domains'] as $domain => $permissions) {
                if (is_array($permissions)) {
                    foreach ($permissions as $permission) {
                        $this->createPermission($domain, $permission);
                    }
                }
            }
        }
    }
}
