<?php

namespace Domain\Role\ViewModels;

use Illuminate\Support\Str;
use Spatie\ViewModels\ViewModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?Role $oldRole = null
    ) {
    }

    public function role(): array
    {
        return [
            'id' => $this->oldRole?->id,
            'name' => $this->oldRole->name ?? '',
            'displayName' => $this->oldRole->display_name ?? '',
            'ability' => $this->oldRole ? $this->getStringFromSlug(explode(' ', $this->oldRole->display_name)[0]) : '',
            'permissions' => $this->oldRole ? collect($this->oldRole->permissions)->map(function ($values) {
                return [
                    'id' => $values['id'],
                ];
            }) : [],
        ];
    }

    public function permissions(): Collection
    {
        return collect(
            Permission::query()
                ->whereNotIn('display_name', ['Move', 'All', 'Rules', 'Return', 'History', 'Assign', 'Upload', 'Fetch', 'Uploading'])
                ->get()
        )->map(function ($values) {
            return [
                'id' => $values['id'],
                'name' => explode('-', $values['name'])[1],
                'displayName' => $values['display_name'],
                'ability' => $this->getStringFromSlug(explode(' ', $values['display_name'])[0]),
                'domain' =>  $values['domain'],
                'checked' => $this->oldRole && $this->checkIfPermissionAssigned($this->oldRole, $values['id']),
            ];
        })->groupBy(function ($item) {
            return $item['domain'];
        });
    }

    protected function checkIfPermissionAssigned(Role $role, int $id): bool
    {
        return DB::table('role_has_permissions')->where([
            'role_id' => $role->id,
            'permission_id' => $id,
        ])->exists();
    }

    protected function getStringFromSlug(string $slug): string
    {
        return Str::title(str_replace('-', ' ', $slug));
    }
}
