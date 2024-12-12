<?php

namespace Domain\WorkshopName\Controllers;

use Domain\WorkshopName\Actions\StoreWorkshopNameAction;
use Domain\WorkshopName\Models\WorkshopName;
use Domain\WorkshopName\Requests\WorkshopNameRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\WorkshopName\Actions\ListWorkshopNamesAction;
use Inertia\Response as InertiaResponse;
use Domain\WorkshopName\ViewModels\WorkshopNameViewModel;
use Domain\WorkshopName\ViewModels\WorkshopNameCreateEditViewModel;
use Support\Helper\Helper;
use Throwable;

class WorkshopNameController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.workshops-names.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new WorkshopNameRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new WorkshopNameViewModel(
            20,
        );

        return Inertia::render('WorkshopsNames/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new WorkshopNameCreateEditViewModel();

        return Inertia::render('WorkshopsNames/Create', $viewModel);
    }

    public function show(WorkshopName $workshopName): InertiaResponse
    {
        $viewModel = new WorkshopNameCreateEditViewModel($workshopName);

        return Inertia::render('WorkshopsNames/Create', $viewModel);
    }

    public function store(
        WorkshopNameRequest $workshopNameRequest,
        StoreWorkshopNameAction $storeWorkshopNameAction,
    ) {
        try {
            DB::beginTransaction();

            $storeWorkshopNameAction->execute(
                $workshopNameRequest->data(),
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'WorkshopName Saved',
                'message' => __('Workshop Name Saved'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        WorkshopName $workshopName,
        WorkshopNameRequest $workshopNameRequest,
        StoreWorkshopNameAction $storeWorkshopNameAction,
    ) {
        try {
            DB::beginTransaction();

            $storeWorkshopNameAction->execute(
                $workshopNameRequest->data(),
                $workshopName
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'WorkshopName Updated',
                'message' => __('Workshop Name Updated'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy( WorkshopName $workshopName)
    {
        try {
            DB::beginTransaction();

            $workshopName->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'WorkshopName Deleted',
                'message' => __('Workshop Name  Deleted'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

}
