<?php

namespace Domain\WorkshopName\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\WorkshopName\Actions\ListWorkshopsNamesAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WorkshopNameController extends Controller
{

    public function index(
        ListWorkshopsNamesAction $listWorkshopsNamesAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched workshop names',
            'workshop-names' => $listWorkshopsNamesAction->execute($params),
        ]);
    }
}