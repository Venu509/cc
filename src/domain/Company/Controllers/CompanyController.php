<?php

namespace Domain\Company\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Candidate\Helpers\CandidateHelper;
use Domain\Company\ViewModels\CompanyCreateEditViewModel;
use Domain\Company\ViewModels\CompanyViewModel;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
use Domain\User\Actions\ListCandidatesAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Support\Helper\Helper;
use Throwable;

class CompanyController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.companies.index';

    public function rules(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new EmployeeRequest())->validations()
        ]);
    }

    public function fetch():JsonResponse
    {
        return response()->json([
            'status' => true,
            'companies' => User::query()
                ->whereHas('roles', function (Builder $builder){
                    return $builder->whereIn('name', ['company']);
                })->select([
                    'id as value',
                    'name as label',
                ])->get()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new CompanyViewModel(
            20,
        );

        return Inertia::render('Companies/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new CompanyCreateEditViewModel();

        return Inertia::render('Companies/Create', $viewModel);
    }

    public function show(User $user): InertiaResponse
    {
        $viewModel = new CompanyCreateEditViewModel($user);

        return Inertia::render('Companies/Create', $viewModel);
    }

    public function store(
        EmployeeRequest $employeeRequest,
        StoreEmployeeAction $storeEmployeeAction,
    ) {
        DB::beginTransaction();

        $user = $storeEmployeeAction->execute(
            $employeeRequest->data(),
        );
        DB::commit();

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Company Saved',
            'message' => __('Company Saved'),
        ]);
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

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Company Updated',
                'message' => __('Company Updated'),
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
                'title' => 'Company Deleted',
                'message' => __('Company :name Deleted', ['name' => $user->first_name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
