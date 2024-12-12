<?php

namespace Domain\College\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\College\Actions\ListCollegesAction;
use Domain\College\Resources\CollegeResources;
use Domain\Global\Actions\StoreEmployeeAction;
use Domain\Global\Requests\EmployeeRequest;
use Domain\User\Actions\ListUsersAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class CollegeController extends Controller
{
    use Helper;

    public function index(
        ListCollegesAction $listCollegesAction
    ): JsonResponse
    {
        $role = auth()->user()->roles()->first()->name;
        $isAssignedTo = !in_array($role, ['admin', 'master'], true);

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
            'type' => request()->has('type') ? request()->get('type') : null,
            'isAssignedTo' => $isAssignedTo,
            'roles' => ['government', 'institution'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched colleges',
            'colleges' => $listCollegesAction->execute($params),
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

        $college = User::where('id', request()->get('id'))->first();

        if (!$college) {
            return response()->json([
                'status' => false,
                'message' => 'College not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched college',
            'college' => CollegeResources::make($college),
        ]);
    }

    public function store(
        EmployeeRequest $employeeRequest,
        StoreEmployeeAction $storeEmployeeAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $college = $storeEmployeeAction->execute(
                $employeeRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'College Saved',
                'college' => CollegeResources::make($college),
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

            $college = User::where('id', $employeeRequest->data()->id)->first();

            $updatedCollege = $storeEmployeeAction->execute(
                $employeeRequest->data(),
                $college
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'College Updated',
                'college' => CollegeResources::make($updatedCollege),
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
                'message' => 'College Deleted',
                'college' => CollegeResources::make($user),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
