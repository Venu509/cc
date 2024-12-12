<?php

namespace Domain\Project\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Project\Actions\ListProjectsAction;
use Domain\Project\Actions\StoreProjectAction;
use Domain\Project\Models\Project;
use Domain\Project\Requests\ProjectRequest;
use Domain\Project\Resources\ProjectResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class ProjectController extends Controller
{
    use Helper;

    public function index(
        ListProjectsAction $listProjectsAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin', 'master'],
            'user' => Auth::user(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched projects',
            'projects' => $listProjectsAction->execute($params),
        ]);
    }

    public function show(): JsonResponse
    {
        if (!request()->has('id')) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the id parameter',
            ]);
        }

        $project = Project::where('id', request()->get('id'))->first();

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched project',
            'project' => ProjectResources::make($project),
        ]);
    }

    public function store(
        ProjectRequest $projectRequest,
        StoreProjectAction $storeProjectAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $project = $storeProjectAction->execute(
                $projectRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Project Saved',
                'project' => ProjectResources::make($project),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        ProjectRequest $projectRequest,
        StoreProjectAction $storeProjectAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $project = Project::where('id', $projectRequest->data()->id)->first();

            $updatedProject = $storeProjectAction->execute(
                $projectRequest->data(),
                $project
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Project Updated',
                'project' => ProjectResources::make($updatedProject),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(Project $project): JsonResponse
    {
        try {
            DB::beginTransaction();

            $project->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Project Deleted',
                'project' => ProjectResources::make($project),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
