<?php

namespace Domain\Lead\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Lead\Actions\ListLeadsAction;
use Domain\Lead\Actions\StoreLeadAction;
use Domain\Lead\Models\Lead;
use Domain\Lead\Requests\LeadRequest;
use Domain\Lead\Resources\LeadResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class LeadController extends Controller
{
    use Helper;

    public function index(
        ListLeadsAction $listLeadsAction
    ): JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched leads',
            'leads' => $listLeadsAction->execute($params),
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

        $lead = Lead::where('id', request()->get('id'))->first();

        if (!$lead) {
            return response()->json([
                'status' => false,
                'message' => 'Lead not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched lead',
            'lead' => LeadResources::make($lead),
        ]);
    }

    public function store(
        LeadRequest $leadRequest,
        StoreLeadAction $storeLeadAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $lead = $storeLeadAction->execute(
                $leadRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Lead Saved',
                'lead' => LeadResources::make($lead),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        LeadRequest $leadRequest,
        StoreLeadAction $storeLeadAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $lead = Lead::where('id', $leadRequest->data()->id)->first();

            $updatedLead = $storeLeadAction->execute(
                $leadRequest->data(),
                $lead
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Lead Updated',
                'lead' => LeadResources::make($updatedLead),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(Lead $lead): JsonResponse
    {
        try {
            DB::beginTransaction();

            $lead->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Lead Deleted',
                'lead' => LeadResources::make($lead),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
