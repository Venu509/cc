<?php

namespace Domain\User\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\API\Authentication\Actions\StoreUserAction;
use Domain\API\Authentication\Requests\RegisterRequest;
use Domain\Candidate\Actions\UpdateCandidateDetailsAction;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
use Domain\Global\Resources\UserDetailsResources;
use Domain\User\Actions\DeleteUserAction;
use Domain\User\Actions\ListAllUsersAction;
use Domain\User\Actions\ListUsersAction;
use Domain\User\Actions\RemoveProfileAvatarAction;
use Domain\User\Actions\UploadProfileAvatarAction;
use Domain\User\Requests\ProfileAvatarDeleteRequest;
use Domain\User\Requests\ProfileAvatarUploadRequest;
use Domain\User\Requests\ProfileDeleteRequest;
use Domain\User\Resources\UserProfileResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class UserController extends Controller
{
    use Helper;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all(
        ListAllUsersAction $listAllUsersAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'role' => request()->has('role') ? request()->get('role') : null,
            'user' => request()->has('user') ? request()->get('user') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'date' => request()->has('date') ? request()->get('date') : null,
        ];

        if (!$params['role']) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the role parameter',
            ]);
        }

        if (!$params['user']) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the user parameter',
            ]);
        }

        if (!findUserById($params['user'])) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched users',
            'users' => $listAllUsersAction->execute($params),
        ]);
    }

    public function index(
        ListUsersAction $listUsersAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'role' => request()->has('role') ? request()->get('role') : null,
            'roles' => request()->has('roles') ? request()->get('roles') : null,
            'user' => request()->has('user') ? request()->get('user') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
        ];

        if (!$params['role']) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the role parameter',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched users',
            'users' => $listUsersAction->execute($params),
        ]);
    }

    public function profile(): JsonResponse
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
            'user' => UserDetailsResources::make(auth()->user()),
        ]);
    }

    public function update(
        EmployeeRequest $employeeRequest,
        UpdateCandidateDetailsAction $updateCandidateDetailsAction,
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = User::find((int)request()->get('id'));

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Requested record not found',
                ], 422);
            }

            $updateCandidateDetailsAction->execute(
                $employeeRequest->data(),
                $user,
                'update'
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Profile updated',
                'user' => UserDetailsResources::make($user),
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function upload(
        ProfileAvatarUploadRequest $profileAvatarUploadRequest,
        UploadProfileAvatarAction  $uploadProfileAvatarAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not logged in',
                ], 401);
            }

            $user = User::where('id', $profileAvatarUploadRequest->data()->user)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 422);
            }

            $uploadProfileAvatarAction->execute($profileAvatarUploadRequest->data(), $user);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Profile avatar uploaded',
                'user' => UserProfileResources::make($user),
            ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->throwable($th);
        }
    }

    public function destroy(
        ProfileAvatarDeleteRequest $profileAvatarDeleteRequest,
        RemoveProfileAvatarAction  $removeProfileAvatarAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not logged in',
                ], 401);
            }

            $user = User::where('id', $profileAvatarDeleteRequest->data()->user)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 422);
            }

            $removeProfileAvatarAction->execute($profileAvatarDeleteRequest->data(), $user);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Profile avatar removed',
                'user' => UserProfileResources::make($user),
            ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->throwable($th);
        }
    }

    public function delete(
        ProfileDeleteRequest $profileDeleteRequest,
        DeleteUserAction  $deleteUserAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = findUserById($profileDeleteRequest->data()->user);

            $deleteUserAction->execute($profileDeleteRequest->data(), findUserById($profileDeleteRequest->data()->user));

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Profile delete',
            ]);
        } catch (Throwable $th) {
            DB::rollBack();

            return $this->throwable($th);
        }
    }
}
