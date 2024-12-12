<?php

namespace Domain\User\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\User\Actions\ListUsersAction;
use Inertia\Response as InertiaResponse;
use Domain\User\ViewModels\UserViewModel;
use Domain\User\Requests\UserAssignRequest;
use Domain\Global\Actions\ManageStatusAction;
use Domain\User\Actions\AssignUserLocationAction;
use Domain\User\Actions\AssignUserHierarchyAction;
use Domain\User\ViewModels\UserCreateEditViewModel;
use Domain\API\Authentication\Actions\StoreUserAction;
use Domain\API\Authentication\Requests\RegisterRequest;
use Support\Helper\Helper;
use Throwable;

class UserController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'users.index';

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new UserViewModel(
            20,
        );

        return Inertia::render('Users/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new UserCreateEditViewModel();

        return Inertia::render('Users/Create', $viewModel);
    }

    public function show(User $user): InertiaResponse
    {
        $viewModel = new UserCreateEditViewModel($user);

        return Inertia::render('Users/Create', $viewModel);
    }

    public function store(
        RegisterRequest $registerRequest,
        StoreUserAction $storeUserAction,
    ) {
        try {
            DB::beginTransaction();

            $storeUserAction->execute(
                $registerRequest->data(),
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'User Saved',
                'message' => __('User :name Saved', ['name' => $registerRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        User $user,
        RegisterRequest $registerRequest,
        StoreUserAction $storeUserAction,
    ) {
        try {
            DB::beginTransaction();

            $storeUserAction->execute(
                $registerRequest->data(),
                $user
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'User Updated',
                'message' => __('User :name Updated', ['name' => $registerRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function status(
        User $user,
        Request $request,
        ManageStatusAction $manageStatusAction,
    ): RedirectResponse {
        $manageStatusAction->execute($user, $request, target: 'isActive');

        return redirect(route(self::INDEX_ROUTE, $request->query()))->withFlash([
            'type' => $request->isActive ? 'success' : 'warning',
            'title' => 'User '.($request->isActive ? 'Approved' : 'Rejected'),
            'message' => __(':name '.($request->isActive ? 'Approved' : 'Rejected'), ['name' => $user->name]),
        ]);
    }

    public function assign(
        UserAssignRequest $userAssignRequest,
        AssignUserLocationAction $assignUserLocationAction,
        AssignUserHierarchyAction $assignUserHierarchyAction,
    ) {
        $user = User::where('id', $userAssignRequest->data()->id)->first();

        $assignUserLocationAction->execute($userAssignRequest->data(), $user);

        if (! ($userAssignRequest->data()->role === 'regional-sales-manager')) {
            $assignUserHierarchyAction->execute($userAssignRequest->data());
        }

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'User Assign',
            'message' => __('User Assign Success'),
        ]);
    }
}
