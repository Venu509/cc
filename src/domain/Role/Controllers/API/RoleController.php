<?php

namespace Domain\Role\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Role\Actions\DestroyRoleAction;
use Domain\Role\Actions\ListRolesAction;
use Domain\Role\Actions\RefreshUserAssignedPermission;
use Domain\Role\Actions\StoreRoleAction;
use Domain\Role\Actions\SyncRoleWithPermissionsAction;
use Domain\Role\Requests\RoleRequest;
use Domain\Role\ViewModels\RoleCreateEditViewModel;
use Domain\Role\ViewModels\RoleViewModel;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(
        ListRolesAction $listRolesAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'role' => request()->has('role') ? request()->get('role') : null,
            'roles' => request()->has('roles') ? request()->get('roles') : null,
        ];

        if (!$params['role']) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the role parameter',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched roles',
            'roles' => $listRolesAction->execute($params),
        ]);
    }

    public function show(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile Info',
            'user' => UserProfileResources::make(auth()->user()),
        ]);
    }
}
