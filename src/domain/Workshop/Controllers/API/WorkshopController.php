<?php

namespace Domain\Workshop\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Workshop\Actions\ListWorkshopsAction;
use Domain\Workshop\Actions\StoreWorkshopAction;
use Domain\Workshop\Models\Workshop;
use Domain\Workshop\Requests\WorkshopRequest;
use Domain\Workshop\Resources\WorkshopResources;
use Domain\Workshop\ViewModels\WorkshopCreateEditViewModel;
use Domain\Workshop\ViewModels\WorkshopViewModel;
use Domain\WorkshopName\Actions\StoreWorkshopNameAction;
use Domain\WorkshopName\Data\WorkshopNameData;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Support\Helper\Helper;
use Throwable;

class WorkshopController extends Controller
{
    use Helper;

    public function index(
        ListWorkshopsAction $listWorkshopsAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'isFiltered' => true,
            'filterRestrictRoles' => ['admin', 'super-admin', 'master'],
            'user' => Auth::user(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched workshops',
            'workshops' => $listWorkshopsAction->execute($params),
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

        $workshop = Workshop::where('id', request()->get('id'))->first();

        if (!$workshop) {
            return response()->json([
                'status' => false,
                'message' => 'Workshop not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched workshop',
            'workshop' => WorkshopResources::make($workshop),
        ]);
    }

    public function store(
        WorkshopRequest $workshopRequest,
        StoreWorkshopAction $storeWorkshopAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $workshop = $storeWorkshopAction->execute(
                $workshopRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Workshop Saved',
                'workshop' => WorkshopResources::make($workshop),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        WorkshopRequest $workshopRequest,
        StoreWorkshopAction $storeWorkshopAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $workshop = Workshop::where('id', $workshopRequest->data()->id)->first();

            $updatedWorkshop = $storeWorkshopAction->execute(
                $workshopRequest->data(),
                $workshop
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Workshop Updated',
                'workshop' => WorkshopResources::make($updatedWorkshop),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(Workshop $workshop): JsonResponse
    {
        try {
            DB::beginTransaction();

            $workshop->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Workshop Deleted',
                'workshop' => WorkshopResources::make($workshop),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
