<?php

namespace Domain\Candidate\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Candidate\Helpers\CandidateHelper;
use Domain\Candidate\ViewModels\CandidateCreateEditViewModel;
use Domain\Candidate\ViewModels\CandidateViewModel;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
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

class CandidateController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.candidates.index';

    public function rules():JsonResponse
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
    public function fetch():JsonResponse
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

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new CandidateViewModel(
            20,
        );

        return Inertia::render('Candidates/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new CandidateCreateEditViewModel();

        return Inertia::render('Candidates/Create', $viewModel);
    }

    public function show(User $user): InertiaResponse
    {
        $viewModel = new CandidateCreateEditViewModel($user);

        return Inertia::render('Candidates/Create', $viewModel);
    }

    public function store(
        EmployeeRequest $employeeRequest,
        StoreEmployeeAction $storeEmployeeAction,
    ) {
        try {
            DB::beginTransaction();

            $user = $storeEmployeeAction->execute(
                $employeeRequest->data(),
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Candidate Saved',
                'message' => __('Candidate Saved'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
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

            $tab = candidateTabs($employeeRequest->data()->tab);

            $route = route('admin.candidates.show', ['tab' => $tab, 'user' => $user->id]);

            if($employeeRequest->data()->tab === 'additional-information') {
                $route = route(self::INDEX_ROUTE);
            }

            DB::commit();

            return redirect($route)->withFlash([
                'type' => 'success',
                'title' => 'Candidate Updated',
                'message' => __('Candidate Updated'),
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
                'title' => 'Candidate Deleted',
                'message' => __('Candidate :name Deleted', ['name' => $user->first_name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
