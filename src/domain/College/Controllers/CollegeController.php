<?php

namespace Domain\College\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\College\ViewModels\CollegeCreateEditViewModel;
use Domain\College\ViewModels\CollegeViewModel;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use JsonException;
use Support\Helper\Helper;
use Throwable;

class CollegeController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.colleges.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new EmployeeRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new CollegeViewModel(
            20,
        );

        return Inertia::render('Colleges/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new CollegeCreateEditViewModel();

        return Inertia::render('Colleges/Create', $viewModel);
    }

    public function show(User $user): InertiaResponse
    {
        $viewModel = new CollegeCreateEditViewModel($user);

        return Inertia::render('Colleges/Create', $viewModel);
    }

    /**
     * @throws JsonException
     */
    public function store(
        EmployeeRequest     $employeeRequest,
        StoreEmployeeAction $storeCollegeAction,
    ) {
        $storeCollegeAction->execute(
            $employeeRequest->data(),
        );

        DB::commit();

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'College Saved',
            'message' => __('College :name Saved', ['name' => $employeeRequest->data()->name]),
        ]);
    }

    public function update(
        User                $user,
        EmployeeRequest     $employeeRequest,
        StoreEmployeeAction $storeCollegeAction,
    ) {
        try {
            DB::beginTransaction();

            $storeCollegeAction->execute(
                $employeeRequest->data(),
                $user,
                'update'
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'College Updated',
                'message' => __('College :name Updated', ['name' => $employeeRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'College Deleted',
                'message' => __('College :name Deleted', ['name' => $user->first_name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
