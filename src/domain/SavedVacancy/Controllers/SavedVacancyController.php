<?php

namespace Domain\SavedVacancy\Controllers;

use Domain\Job\ViewModels\JobViewModel;
use Domain\SavedVacancy\Actions\StoreSavedVacancyAction;
use Domain\SavedVacancy\Models\SavedVacancy;
use Domain\SavedVacancy\Requests\SavedVacancyRequest;
use Domain\SavedVacancy\ViewModels\SavedVacancyViewModel;
use Domain\Vacancy\Actions\ListVacanciesAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;
use Support\Helper\Helper;
use Throwable;

class SavedVacancyController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.saved-jobs.index';


    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new JobViewModel(
            20,
        );

        return Inertia::render('SavedVacancies/Index', $viewModel);
    }

    public function store(
        SavedVacancyRequest $savedJobRequest,
        StoreSavedVacancyAction $storeSavedVacancyAction,
    ) {
        $exsistData = SavedVacancy::where('candidate_id', auth()->id())
            ->where('vacancy_id', $savedJobRequest->data()->vacancyId);

        if ($exsistData->exists()){

            $exsistData->first()->delete();

            return redirect()->back()->withFlash([
                'type' => 'success',
                'title' => 'Removing success',
                'message' => __('Vacancy removed successfully from saved list'),
            ]);
        }
        try {
            DB::beginTransaction();

                $storeSavedVacancyAction->execute(
                    $savedJobRequest->data(),
                );

            DB::commit();

            return redirect()->back()->withFlash([
                'type' => 'success',
                'title' => 'Saved job successfully',
                'message' => __('Vacancy added to saved list successfully'),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function update(
        SavedVacancy $savedJob,
        SavedVacancyRequest $savedJobRequest,
        StoreSavedVacancyAction $storeSavedVacancyAction,
    ) {
        try {
            DB::beginTransaction();

            $storeSavedVacancyAction->execute(
                $savedJobRequest->data(),
                $savedJob
            );

            DB::commit();

            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'success',
                'title' => 'SavedVacancy Updated',
                'message' => __('SavedVacancy :name Updated', ['name' => $savedJobRequest->data()->name]),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function destroy(SavedVacancy $savedJob)
    {
        try {
            DB::beginTransaction();

            $savedJob->delete();

            DB::commit();

        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e);
        }
    }

    public function fetch(
        ListVacanciesAction $listVacanciesAction
    ): JsonResponse {

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 20,
            'page' => request()->has('page') ? request()->get('page') : 1,
            'search' => request()->has('search') ? request()->get('search') : null,
            'keywords' => request()->has('keywords') ? request()->get('keywords') : null,
            'locations' => request()->has('locations') ? request()->get('locations') : null,
            'qualifications' => request()->has('qualifications') ? request()->get('qualifications') : null,
            'jobTypes' => request()->has('job-types') ? request()->get('job-types') : null,
            'industries' => request()->has('industries') ? request()->get('industries') : null,
            'numberOfExperiences' => request()->has('number-of-experiences') ? request()->get('number-of-experiences') : null,
            'noticePeriod' => request()->has('notice-periods') ? request()->get('notice-periods') : null,
            'employmentStatus' => request()->has('employment-status') ? request()->get('employment-status') : null,
            'postedDate' => request()->has('postedDate') ? request()->get('postedDate') : null,
            'appliedJobs' => request()->has('appliedJobs') ? request()->get('appliedJobs') : null,
            'isSavedJobs' => true,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetch jobs',
            'jobs' => $listVacanciesAction->execute($params),
        ]);
    }

}
