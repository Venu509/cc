<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Support\Helper\Helper;

class SyncPermissions extends Command
{
    use Helper;

    protected $signature = 'app:sync-permissions';
    protected $description = 'Sync newly available domains and permissions';

    public function handle(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        collect(roleAbilities())->each(function ($ability, $roleSlug) {
            $role = Role::firstOrCreate(
                ['name' => $roleSlug],
                ['display_name' => getStringFromSlug($roleSlug)]
            );

            $abilities = collect($ability['domains'])->flatMap(function ($permissions, $domain) {
                return collect($permissions)->map(function ($permission) use ($domain) {
                    $permissionName = "{$permission}-{$domain}";

                    $this->createPermission($domain, $permission);

                    return $permissionName;
                });
            })->toArray();

            $role->syncPermissions($abilities);
        });

        User::with('roles')->each(function ($user) {
            $role = $user->roles->first();
            if ($role) {
                $permissions = $role->permissions->pluck('name')->toArray();
                $user->syncPermissions($permissions);
            }
        });
    }
}
