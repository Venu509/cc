<?php

namespace Domain\Candidate\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
use Domain\User\Actions\ListCandidatesAction;
use Domain\User\Resources\CandidateResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class CandidateController extends Controller
{
    use Helper;

    public function index(
        ListCandidatesAction $listCandidatesAction
    ): JsonResponse
    {
        $role = auth()->user()->roles()->first()->name;
        $isAssignedTo = !in_array($role, ['admin', 'master'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'keywords' => request()->has('keywords') ? request()->get('keywords') : null,
            'locations' => request()->has('locations') ? request()->get('locations') : null,
            'qualifications' => request()->has('qualifications') ? request()->get('qualifications') : null,
            'candidateTypes' => request()->has('candidate-types') ? request()->get('candidate-types') : null,
            'industries' => request()->has('industries') ? request()->get('industries') : null,
            'numberOfExperiences' => request()->has('number-of-experiences') ? request()->get('number-of-experiences') : null,
            'noticePeriod' => request()->has('notice-periods') ? request()->get('notice-periods') : null,
            'employmentStatus' => request()->has('employment-status') ? request()->get('employment-status') : null,
            'vacancyId' =>  request()->has('vacancyId') ? request()->get('vacancyId') : null,
            'tab' => request()->has('tab') ? request()->get('tab') : null,
            'isAssignedTo' => $isAssignedTo,
            'roles' => ['candidate'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched candidates',
            'candidates' => $listCandidatesAction->execute($params),
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

        $candidate = User::where('id', request()->get('id'))->first();

        if (!$candidate) {
            return response()->json([
                'status' => false,
                'message' => 'Candidate not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched candidate',
            'candidate' => CandidateResources::make($candidate),
        ]);
    }

    public function store(
        EmployeeRequest $employeeRequest,
        StoreEmployeeAction $storeEmployeeAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $candidate = $storeEmployeeAction->execute(
                $employeeRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Candidate Saved',
                'candidate' => CandidateResources::make($candidate),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        EmployeeRequest $employeeRequest,
        StoreEmployeeAction $storeEmployeeAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $candidate = User::where('id', $employeeRequest->data()->id)->first();

            $updatedCandidate = $storeEmployeeAction->execute(
                $employeeRequest->data(),
                $candidate,
                'update'
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Candidate Updated',
                'candidate' => CandidateResources::make($updatedCandidate),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Candidate Deleted',
                'candidate' => CandidateResources::make($user),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
