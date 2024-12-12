<?php

namespace Domain\Job\Controllers;

use App\Http\Controllers\Controller;
use Domain\Job\Actions\StoreJobApplyAction;
use Domain\Job\Actions\StoreVacancyAnswersAction;
use Domain\Job\ViewModels\JobShowViewModel;
use Domain\Job\ViewModels\JobViewModel;
use Domain\Vacancy\Actions\ListVacanciesAction;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Requests\UserVacancyRequest;
use Domain\Vacancy\ViewModels\VacancyCreateEditViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class JobController extends Controller
{
    public const INDEX_ROUTE = 'admin.jobs.index';

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new JobViewModel(
            20,
        );

        return Inertia::render('Jobs/List', $viewModel);
    }

    public function show(Vacancy $vacancy): InertiaResponse|RedirectResponse
    {
        $viewModel = new JobShowViewModel(
            $vacancy,
        );

        return Inertia::render('Jobs/Show', $viewModel);
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
            'postedDate' => request()->has('posted-date') ? request()->get('posted-date') : null,
            'appliedJobs' => request()->has('applied-jobs') ? request()->get('applied-jobs') === 'yes' : null,
            'minSalary' => request()->has('min-salary') ? request()->get('min-salary') : null,
            'maxSalary' => request()->has('max-salary') ? request()->get('max-salary') : null,
            'roles' => ['candidate'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetch jobs',
            'jobs' => $listVacanciesAction->execute($params),
        ]);
    }

    public function store(
        UserVacancyRequest $userVacancyRequest,
        StoreJobApplyAction $storeJobApplyAction,
        StoreVacancyAnswersAction $storeVacancyAnswersAction
    )
    {
        if (UserVacancy::query()->where('candidate_id', Auth::user()->id)->where('vacancy_id', $userVacancyRequest->data()->vacancyId)->exists()){
            return redirect(route(self::INDEX_ROUTE))->withFlash([
                'type' => 'error',
                'title' => 'Already Applied',
                'message' => __('You have already applied to this vacancy'),
            ]);
        }

       $storeJobApplyAction->execute($userVacancyRequest->data());

        if($userVacancyRequest->data()->answers !== null){
            $storeVacancyAnswersAction->execute(
                Vacancy::where('id', $userVacancyRequest->data()->vacancyId)->first(),
                $userVacancyRequest->data()->answers
            );
        }

        return redirect()->back()->withFlash([
            'type' => 'success',
            'title' => 'Apply job',
            'message' => __('You have successfully applied to the :name', ['name' => Vacancy::query()->where('id', $userVacancyRequest->data()->vacancyId)->first()->title]),
        ]);
    }
}
