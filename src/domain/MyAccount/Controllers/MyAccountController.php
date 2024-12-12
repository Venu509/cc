<?php

namespace Domain\MyAccount\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Candidate\Helpers\CandidateHelper;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
use Domain\MyAccount\ViewModels\MyAccountViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Support\Helper\Helper;
use Throwable;

class MyAccountController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.my-accounts.show';

    public function rules(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new EmployeeRequest())->validations()
        ]);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws JsonException
     */
    public function fetch(): JsonResponse
    {
        if(!request()->has('candidate')) {
            return response()->json([
                'status' => false,
                'candidate' => null,
            ]);
        }

        $candidate = findUserById(request()->get('candidate'));

        return response()->json([
            'status' => true,
            'candidate' => (new CandidateHelper())->data($candidate)
        ]);
    }

    public function show(User $user): InertiaResponse
    {
        $viewModel = new MyAccountViewModel($user);

        if (auth()->check() && auth()->user()->roles()->first()->name !== 'candidate') {
            return Inertia::render('Profile/Show', $viewModel);
        }

        return Inertia::render('Profile/MyAccount', $viewModel);
    }

    public function update(
        User $user,
        EmployeeRequest $employeeRequest,
        StoreEmployeeAction $storeEmployeeAction,
    ) {
        try {
            DB::beginTransaction();

            $user = $storeEmployeeAction->execute(
                $employeeRequest->data(),
                $user,
                'update'
            );

            DB::commit();

            return redirect(route('admin.my-accounts.show', ['tab' => 'personal-details', 'user' => $user->id]))->withFlash([
                'type' => 'success',
                'title' => 'Candidate Updated',
                'message' => __('Candidate Updated'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function info(
        User $user,
        Request $request,
    ) {

        try {
            DB::beginTransaction();

            $user->forceFill([
                'name' => $request->name,
                'email' => $request->email,
                'avatar' => $this->saveFile(
                    $user,
                    $request->photo,
                    'avatar',
                    'user-details/avatars/',
                ),
            ]);

            $user->save();
            $user->refresh();

            DB::commit();

            return redirect(route('admin.my-accounts.show', ['user' => $user->id]))->withFlash([
                'type' => 'success',
                'title' => 'User Updated',
                'message' => __('User Updated'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function remove(
        User $user,
        Request $request,
    ) {

        try {
            DB::beginTransaction();

            $storageDisk = env('DEFAULT_STORAGE', 'public');

            $this->deleteFileIfExists('user-details/avatars/thumbnail/' . $user->avatar, $storageDisk);

            $user->forceFill([
                'avatar' => null,
            ]);

            $user->save();
            $user->refresh();

            DB::commit();

            return redirect(route('admin.my-accounts.show', ['user' => $user->id]))->withFlash([
                'type' => 'success',
                'title' => 'Profile image removed',
                'message' => __('User Profile image removed'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
