<?php

namespace Domain\Branch\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Branch\Actions\ListBranchesAction;
use Domain\Branch\Actions\StoreBranchAction;
use Domain\Branch\Models\Branch;
use Domain\Branch\Requests\BranchDeleteRequest;
use Domain\Branch\Requests\BranchRequest;
use Domain\Branch\Resources\BranchResources;
use Domain\Global\Actions\DestroyModelAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class BranchController extends Controller
{
    use Helper;

    public function index(
        ListBranchesAction $listBranchesAction
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
            'message' => 'Successfully fetched branches',
            'branches' => $listBranchesAction->execute($params),
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

        $branch = Branch::where('id', request()->get('id'))->first();

        if (!$branch) {
            return response()->json([
                'status' => false,
                'message' => 'Branch not Found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched branch',
            'branch' => BranchResources::make($branch),
        ]);
    }

    public function store(
        BranchRequest $branchRequest,
        StoreBranchAction $storeBranchAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $branch = $storeBranchAction->execute(
                $branchRequest->data(),
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Branch Saved',
                'branch' => BranchResources::make($branch),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        BranchRequest $branchRequest,
        StoreBranchAction $storeBranchAction,
    ): JsonResponse {
        try {
            DB::beginTransaction();

            $branch = Branch::where('id', request()->get('id'))->first();

            $updatedBranch = $storeBranchAction->execute(
                $branchRequest->data(),
                $branch
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Branch Updated',
                'branch' => BranchResources::make($updatedBranch),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(
        BranchDeleteRequest $branchDeleteRequest,
        DestroyModelAction $destroyModelAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            $destroyModelAction->execute(Branch::where('id', $branchDeleteRequest->data()->id)->first());

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Branch Deleted',
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }
}
