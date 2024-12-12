<?php

namespace Domain\Lead\Controllers;

use App\Http\Controllers\Controller;
use Domain\Global\Actions\DestroyModelAction;
use Domain\Global\Actions\ManageStatusAction;
use Domain\Global\Requests\EmployeeRequest;
use Domain\Lead\Actions\StoreLeadAction;
use Domain\Lead\Models\Lead;
use Domain\Lead\Requests\LeadRequest;
use Domain\Lead\ViewModels\LeadCreateEditViewModel;
use Domain\Lead\ViewModels\LeadViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class LeadController extends Controller
{
    public const INDEX_ROUTE = 'admin.leads.index';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new LeadRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse {
        $viewModel = new LeadViewModel();

        return Inertia::render('Leads/Index', $viewModel);
    }

    public function create(): InertiaResponse {
        $viewModel = new LeadCreateEditViewModel();

        return Inertia::render('Leads/Create', $viewModel);
    }

    public function show(
        Lead $lead,
    ): InertiaResponse {
        $viewModel = new LeadCreateEditViewModel(
            $lead
        );

        return Inertia::render('Leads/Create', $viewModel);
    }

    public function store(
        LeadRequest $leadRequest,
        StoreLeadAction $storeLeadAction
    ): RedirectResponse {

        $existingLeadBuilder = Lead::where('title', $leadRequest->data()->title)
            ->where('type', $leadRequest->data()->type)
            ->where('user_id', auth()->user()->id);

        if($existingLeadBuilder->exists()) {
            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'error',
                'title' => 'Lead is already exist',
                'message' => __('Lead :name is already exist', ['name' => $leadRequest->data()->title]),
            ]);
        }

        $storeLeadAction->execute(
            $leadRequest->data()
        );

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Lead Saved',
            'message' => __('Lead :name Saved', ['name' => $leadRequest->data()->title]),
        ]);
    }

    public function update(
        Lead $lead,
        LeadRequest $leadRequest,
        StoreLeadAction $storeLeadAction
    ): RedirectResponse {

        $storeLeadAction->execute(
            $leadRequest->data(),
            $lead,
        );

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Lead Updated',
            'message' => __('Lead :name Updated', ['name' => $leadRequest->data()->title]),
        ]);
    }

    public function status(
        Lead $lead,
        Request $request,
        ManageStatusAction $manageStatusAction,
    ): RedirectResponse {
        $manageStatusAction->execute($lead, $request, target: 'isActive');

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Lead Updated',
            'message' => __('Lead :name Status Updated', ['name' => $lead->title]),
        ]);
    }

    public function destroy(
        Lead $lead,
        DestroyModelAction $destroyModelAction
    ): RedirectResponse {
        $destroyModelAction->execute($lead);

        return redirect(route(self::INDEX_ROUTE))->withFlash([
            'type' => 'success',
            'title' => 'Lead Deleted',
            'message' => __('Lead :name Delete', ['name' => $lead->title]),
        ]);
    }
}
