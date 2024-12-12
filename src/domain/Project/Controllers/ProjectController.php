<?php

namespace Domain\Project\Controllers;

use Domain\Branch\Requests\BranchRequest;
use Domain\Project\Actions\StoreProjectAction;
use Domain\Project\Models\Project;
use Domain\Project\Requests\ProjectRequest;
use Domain\ProjectName\Actions\StoreProjectNameAction;
use Domain\ProjectName\Data\ProjectNameData;
use Domain\ProjectName\Models\ProjectName;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\Project\Actions\ListProjectsAction;
use Inertia\Response as InertiaResponse;
use Domain\Project\ViewModels\ProjectViewModel;
use Domain\Project\ViewModels\ProjectCreateEditViewModel;
use Support\Helper\Helper;
use Throwable;

class ProjectController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.projects.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new ProjectRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new ProjectViewModel(
            20,
        );

        return Inertia::render('Projects/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new ProjectCreateEditViewModel();

        return Inertia::render('Projects/Create', $viewModel);
    }

    public function show(Project $project): InertiaResponse
    {
        $viewModel = new ProjectCreateEditViewModel($project);

        return Inertia::render('Projects/Create', $viewModel);
    }

    public function store(
        ProjectRequest $projectRequest,
        StoreProjectAction $storeProjectAction,
    ) {

        try {
            DB::beginTransaction();
            $projectNameQuarry = ProjectName::query()->where('id', $projectRequest->data()->name['value']);

            $projectData =  $projectRequest->data();

            if (!$projectNameQuarry->exists()){
                $projectData->name = $this->createNewName($projectRequest);
            }

            $storeProjectAction->execute(
                $projectData
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Project Saved',
                'message' => __('Project :name Saved', ['name' => $projectRequest->data()->name['label']]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        Project $project,
        ProjectRequest $projectRequest,
        StoreProjectAction $storeProjectAction,
    ) {
        try {
            DB::beginTransaction();

            $projectNameQuarry = ProjectName::query()->where('id', $projectRequest->data()->name['value']);

            $projectData =  $projectRequest->data();

            if (!$projectNameQuarry->exists()){
                $projectData->name = $this->createNewName($projectRequest);
            }


            $storeProjectAction->execute(
                $projectData,
                $project
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Project Updated',
                'message' => __('Project :name Updated', ['name' => $projectRequest->data()->name['label']]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy( Project $project)
    {
        try {
            DB::beginTransaction();

            $project->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Project Deleted',
                'message' => __('Project Deleted'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    private function createNewName(ProjectRequest $projectRequest): array
    {
        $newProjectName = (new StoreProjectNameAction())->execute(
            (new ProjectNameData(
                null,
                $projectRequest->data()->name['label']
            ))
        );

        return ['label' => $newProjectName->name, 'value'  => $newProjectName->id];
    }
}
