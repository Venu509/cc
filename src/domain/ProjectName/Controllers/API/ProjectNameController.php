<?php

namespace Domain\ProjectName\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\ProjectName\Actions\ListProjectsNamesAction;
use Illuminate\Http\JsonResponse;

class ProjectNameController extends Controller
{
    public function index(
        ListProjectsNamesAction $listProjectsNamesAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched project names',
            'project-names' => $listProjectsNamesAction->execute($params),
        ]);
    }
}