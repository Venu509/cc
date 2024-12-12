<?php

namespace Domain\Workshop\Controllers;

use Domain\Workshop\Actions\StoreWorkshopAction;
use Domain\Workshop\Data\WorkshopData;
use Domain\Workshop\Models\Workshop;
use Domain\Workshop\Requests\WorkshopRequest;
use Domain\WorkshopName\Actions\StoreWorkshopNameAction;
use Domain\WorkshopName\Data\WorkshopNameData;
use Domain\WorkshopName\Models\WorkshopName;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\Workshop\Actions\ListWorkshopsAction;
use Inertia\Response as InertiaResponse;
use Domain\Workshop\ViewModels\WorkshopViewModel;
use Domain\Workshop\ViewModels\WorkshopCreateEditViewModel;
use Support\Helper\Helper;
use Throwable;

class WorkshopController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.workshops.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new WorkshopRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new WorkshopViewModel(
            20,
        );

        return Inertia::render('Workshops/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new WorkshopCreateEditViewModel();

        return Inertia::render('Workshops/Create', $viewModel);
    }

    public function show(Workshop $workshop): InertiaResponse
    {
        $viewModel = new WorkshopCreateEditViewModel($workshop);

        return Inertia::render('Workshops/Create', $viewModel);
    }

    public function store(
        WorkshopRequest $workshopRequest,
        StoreWorkshopAction $storeWorkshopAction,
    ) {
        try {
            DB::beginTransaction();

            $workshopNameQuarry = WorkshopName::query()->where('id', $workshopRequest->data()->name['value']);

            $workshopData =  $workshopRequest->data();

            if (!$workshopNameQuarry->exists()){
                $workshopData->name = $this->createNewName($workshopRequest);
            }

            $storeWorkshopAction->execute(
                $workshopData
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Workshop Saved',
                'message' => __('Workshop :name Saved', ['name' => $workshopRequest->data()->name['label']]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        Workshop $workshop,
        WorkshopRequest $workshopRequest,
        StoreWorkshopAction $storeWorkshopAction,
    ) {
        try {
            DB::beginTransaction();
            $workshopNameQuarry = WorkshopName::query()->where('id', $workshopRequest->data()->name['value']);

            $workshopData =  $workshopRequest->data();

            if (!$workshopNameQuarry->exists()){
                $workshopData->name = $this->createNewName($workshopRequest);
            }

            $storeWorkshopAction->execute(
                $workshopData,
                $workshop
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Workshop Saved',
                'message' => __('Workshop :name Saved', ['name' => $workshopRequest->data()->name['label']]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy( Workshop $workshop)
    {
        try {
            DB::beginTransaction();

            $workshop->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Workshop Deleted',
                'message' => __('Workshop :name Deleted', ['name' => $workshop->first_name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    private function createNewName(WorkshopRequest $workshopRequest): array
    {
        $newWorkshopName = (new StoreWorkshopNameAction())->execute(
            (new WorkshopNameData(
                null,
                $workshopRequest->data()->name['label']
            ))
        );

       return ['label' => $newWorkshopName->name, 'value'  => $newWorkshopName->id];
    }

}
