<?php

namespace Domain\ProjectName\Controllers;

use Domain\ProjectName\Actions\StoreProjectNameAction;
use Domain\ProjectName\Models\ProjectName;
use Domain\ProjectName\Requests\ProjectNameRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\ProjectName\Actions\ListProjectNamesAction;
use Inertia\Response as InertiaResponse;
use Domain\ProjectName\ViewModels\ProjectNameViewModel;
use Domain\ProjectName\ViewModels\ProjectNameCreateEditViewModel;
use Support\Helper\Helper;
use Throwable;

class ProjectNameController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.projects-names.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new ProjectNameRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new ProjectNameViewModel(
            20,
        );

        return Inertia::render('ProjectsNames/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new ProjectNameCreateEditViewModel();

        return Inertia::render('ProjectsNames/Create', $viewModel);
    }

    public function show(ProjectName $projectName): InertiaResponse
    {
        $viewModel = new ProjectNameCreateEditViewModel($projectName);

        return Inertia::render('ProjectsNames/Create', $viewModel);
    }

    public function store(
        ProjectNameRequest $projectNameRequest,
        StoreProjectNameAction $storeProjectNameAction,
    ) {
        try {
            DB::beginTransaction();

            $storeProjectNameAction->execute(
                $projectNameRequest->data(),
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'ProjectName Saved',
                'message' => __('ProjectName :name Saved', ['name' => $projectNameRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        ProjectName $projectName,
        ProjectNameRequest $projectNameRequest,
        StoreProjectNameAction $storeProjectNameAction,
    ) {
        try {
            DB::beginTransaction();

            $storeProjectNameAction->execute(
                $projectNameRequest->data(),
                $projectName
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'ProjectName Updated',
                'message' => __('ProjectName :name Updated', ['name' => $projectNameRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy( ProjectName $projectName)
    {
        try {
            DB::beginTransaction();

            $projectName->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'ProjectName Deleted',
                'message' => __('ProjectName :name Deleted', ['name' => $projectName->first_name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

}
