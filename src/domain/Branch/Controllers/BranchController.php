<?php

namespace Domain\Branch\Controllers;

use Domain\Branch\Actions\StoreBranchAction;
use Domain\Branch\Models\Branch;
use Domain\Branch\Requests\BranchRequest;
use Domain\Branch\ViewModels\BranchSingleViewModel;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\Branch\Actions\ListBranchesAction;
use Inertia\Response as InertiaResponse;
use Domain\Branch\ViewModels\BranchViewModel;
use Domain\Branch\ViewModels\BranchCreateEditViewModel;
use Support\Helper\Helper;
use Throwable;

class BranchController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.branches.index';

    public function rules():JsonResponse
    {
        return response()->json([
            'status' => true,
            'rules' => (new BranchRequest())->validations()
        ]);
    }

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new BranchViewModel(
            20,
        );

        return Inertia::render('Branches/Index', $viewModel);
    }

    public function create(): InertiaResponse
    {
        $viewModel = new BranchCreateEditViewModel();

        return Inertia::render('Branches/Create', $viewModel);
    }

    public function show(Branch $branch): InertiaResponse
    {
        $viewModel = new BranchCreateEditViewModel($branch);

        return Inertia::render('Branches/Create', $viewModel);
    }

    public function view(Branch $branch): InertiaResponse
    {
        $viewModel = new BranchSingleViewModel($branch);

        return Inertia::render('Branches/View', $viewModel);
    }

    public function store(
        BranchRequest $branchRequest,
        StoreBranchAction $storeBranchAction,
    ) {
        try {
            DB::beginTransaction();

            $storeBranchAction->execute(
                $branchRequest->data(),
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Branch Saved',
                'message' => __('Branch :name Saved', ['name' => $branchRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        Branch $branch,
        BranchRequest $branchRequest,
        StoreBranchAction $storeBranchAction,
    ) {
        try {
            DB::beginTransaction();

            $storeBranchAction->execute(
                $branchRequest->data(),
                $branch
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Branch Updated',
                'message' => __('Branch :name Updated', ['name' => $branchRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(Branch $branch)
    {
        try {
            DB::beginTransaction();

            $branch->delete();

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'Branch Deleted',
                'message' => __('Student :name Deleted', ['name' => $branch->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

}
