<?php

namespace Domain\Company\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Company\Actions\ListCompaniesAction;
use Domain\Company\Resources\CompanyResources;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
use Domain\User\Actions\ListUsersAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class CompanyController extends Controller
{
    use Helper;

    public function index(
        ListCompaniesAction $listCompaniesAction
    ): JsonResponse
    {
        $role = auth()->user()->roles()->first()->name;
        $isAssignedTo = !in_array($role, ['admin', 'master'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'isAssignedTo' => $isAssignedTo,
            'roles' => ['company'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched companies',
            'companies' => $listCompaniesAction->execute($params),
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

        $company = User::where('id', request()->get('id'))->first();

        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched company',
            'company' => CompanyResources::make($company),
        ]);
    }

    public function store(
        EmployeeRequest $employeeRequest,
        StoreEmployeeAction $storeEmployeeAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $company = $storeEmployeeAction->execute(
                $employeeRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Company Saved',
                'company' => CompanyResources::make($company),
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

            $company = User::where('id', $employeeRequest->data()->id)->first();

            $updatedCompany = $storeEmployeeAction->execute(
                $employeeRequest->data(),
                $company
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Company Updated',
                'company' => CompanyResources::make($updatedCompany),
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
                'message' => 'Company Deleted',
                'company' => CompanyResources::make($user),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
