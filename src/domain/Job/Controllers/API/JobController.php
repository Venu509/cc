<?php

namespace Domain\Job\Controllers\API;

use App\Http\Controllers\Controller;
use Domain\Global\Actions\DestroyModelAction;
use Domain\Global\Actions\NotifyCandidateAction;
use Domain\Job\Actions\AssignAppliedStatusToJobAction;
use Domain\Job\Actions\StoreJobApplyAction;
use Domain\Job\Actions\StoreVacancyAnswersAction;
use Domain\Job\Requests\AssignAppliedStatusToJobRequest;
use Domain\Vacancy\Actions\ChangeApplicantStatusAction;
use Domain\Vacancy\Actions\ListVacanciesAction;
use Domain\Vacancy\Actions\StoreVacancyAction;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Requests\ChangeApplicantStatusRequest;
use Domain\Vacancy\Requests\DeleteVacancyRequest;
use Domain\Vacancy\Requests\UserVacancyRequest;
use Domain\Vacancy\Requests\VacancyRequest;
use Domain\Vacancy\Resources\VacancyResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Support\Helper\Helper;
use Throwable;

class JobController extends Controller
{
    use Helper;

    public function index():JsonResponse
    {
        $params = [
            'search' => request()->has('search') ? request()->get('search') : null,
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'page' => request()->has('page') ? request()->get('page') : 1,
            'keywords' => request()->has('keywords') ? request()->get('keywords') : null,
            'locations' => request()->has('locations') ? request()->get('locations') : null,
            'postedDate' => request()->has('posted-date') ? request()->get('posted-date') : null,
            'appliedJobs' => request()->has('applied-jobs') ? request()->get('applied-jobs') === 'yes' : null,
            'applicationMethod' => request()->has('application-method') ? request()->get('application-method') : null,
            'isMatchedJobs' => request()->has('is-matched-jobs') ? request()->get('is-matched-jobs') === 'yes' : null,
            'isSavedJobs' => request()->has('is-saved-jobs') ? request()->get('is-saved-jobs') === 'yes' : null,
            'isBelongsToMe' => auth()->user()->roles()->first()->name !== 'candidate',
            'isApplied' => request()->has('is-applied') ? request()->get('is-applied') === 'yes' : null,
            'tab' => request()->has('tab') ? request()->get('tab') : null,
        ];

        if (request()->has('is-applied') && !request()->has('tab')) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the tab when isApplied is yes.'
            ], 422);
        }

        if (request()->has('tab') && !request()->has('is-applied')) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide isApplied when a tab is provided.'
            ], 422);
        }

        $params['tab'] = [
            'pending' => ['applied', 'viewed'],
            'shortlisted' => ['shortlisted'],
            'rejected' => ['rejected']
        ][$params['tab']] ?? $params['tab'];

        return response()->json([
            'status' => true,
            'jobs' => (new ListVacanciesAction())->execute($params)
        ]);
    }

    public function show(): JsonResponse
    {
        if(!request()->has('id')) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the job id'
            ], 422);
        }

        $jobBuilder = Vacancy::query()->where('id', request()->get('id'));

        if ($jobBuilder->doesntExist()) {
            return response()->json([
                'status' => false,
                'message' => 'Requested record not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'job' => VacancyResources::make($jobBuilder->first())
        ]);
    }

    public function store(
        VacancyRequest $vacancyRequest,
        StoreVacancyAction $storeVacancyAction,
        NotifyCandidateAction $notifyCandidateAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            $vacancy = $storeVacancyAction->execute(
                $vacancyRequest->data(),
            );

            $notifyCandidateAction->execute($vacancy, $vacancyRequest->data());

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Vacancy (:name) Saved', ['name' => $vacancyRequest->data()->title]),
                'job' => VacancyResources::make($vacancy),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e, true);
        }
    }

    public function update(
        VacancyRequest $vacancyRequest,
        StoreVacancyAction $storeVacancyAction,
        NotifyCandidateAction $notifyCandidateAction
    ): JsonResponse
    {
        try {
            DB::beginTransaction();

            $vacancy = Vacancy::where('id', $vacancyRequest->data()->id)->first();

            $updatedVacancy = $storeVacancyAction->execute(
                $vacancyRequest->data(),
                $vacancy
            );

            $notifyCandidateAction->execute($updatedVacancy, $vacancyRequest->data());

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Vacancy (:name) Updated', ['name' => $vacancyRequest->data()->title]),
                'job' => VacancyResources::make($updatedVacancy),
            ]);
        }  catch (Throwable $e) {
            DB::rollBack();

            return $this->throwable($e, true);
        }
    }

    public function apply(
        UserVacancyRequest $userVacancyRequest,
        StoreJobApplyAction $storeJobApplyAction,
        StoreVacancyAnswersAction $storeVacancyAnswersAction
    ): JsonResponse
    {
        $userVacancyBuilder = UserVacancy::query()
            ->where('candidate_id', Auth::user()->id)
            ->where('vacancy_id', $userVacancyRequest->data()->vacancyId);

        if ($userVacancyBuilder->exists()) {
            return response()->json([
                'type' => 'error',
                'title' => 'Already Applied',
                'message' => __('You have already applied to this vacancy'),
            ], 400);
        }

        $vacancy = $storeJobApplyAction->execute($userVacancyRequest->data());

        $vacancy->refresh();

        if ($userVacancyRequest->data()->answers !== null) {
            $storeVacancyAnswersAction->execute(
                $vacancy,
                $userVacancyRequest->data()->answers
            );
        }

        return response()->json([
            'type' => 'success',
            'title' => 'Apply Job',
            'message' => __('You have successfully applied to the :name', [
                'name' => $vacancy->title
            ]),
        ]);
    }

    public function assign(
        AssignAppliedStatusToJobRequest $assignAppliedStatusToJobRequest,
        AssignAppliedStatusToJobAction $assignAppliedStatusToJobAction
    ): JsonResponse {

        $jobBuilder = Vacancy::where('id', $assignAppliedStatusToJobRequest->data()->id);

        $job = $jobBuilder->first();

        $assignAppliedStatusToJobAction->execute(
            $assignAppliedStatusToJobRequest->data(),
            $job
        );

        $job->refresh();

        return response()->json([
            'status' => true,
            'message' => 'Successfully updated the job status',
            'jobs' => VacancyResources::make($job),
        ]);
    }

    public function status(
        ChangeApplicantStatusRequest $changeApplicantStatusRequest,
        ChangeApplicantStatusAction $changeApplicantStatusAction
    ): JsonResponse
    {
        $userVacancy = UserVacancy::where('vacancy_id', $changeApplicantStatusRequest->data()->vacancy)
            ->where('candidate_id', $changeApplicantStatusRequest->data()->resume)->first();

        if ($userVacancy) {
            $trackingHistory = collect($userVacancy->history);

            $historyCount = $trackingHistory->count();

            if ($changeApplicantStatusRequest->data()->isAxiosRequest) {
                if ($historyCount === 1 && $userVacancy->status === 'applied') {
                    $changeApplicantStatusAction->execute(
                        $changeApplicantStatusRequest->data(),
                        $userVacancy
                    );
                }
            } else {
                $changeApplicantStatusAction->execute(
                    $changeApplicantStatusRequest->data(),
                    $userVacancy
                );
            }
        } else {
            return response()->json([
                'status' => false,
                'title' => 'Vacancy is not found',
                'message' => 'This candidate is not yet applied for this job',
            ]);
        }

        $status = $changeApplicantStatusRequest->data()->status;

        $responseMsg = [
            'status' => true,
            'type' => 'success',
            'title' => __(':state successful', ['state' => ucfirst($status === 'viewed' ? 'pending' : $status)]),
            'message' => __('You have successfully :state candidate (:name)', [
                'name' => findUserById($changeApplicantStatusRequest->data()->resume)->name,
                'state' => ucfirst($status === 'viewed' ? 'pending' : $status)
            ]),
        ];

        return response()->json($responseMsg);
    }

    public function destroy(
        DeleteVacancyRequest $deleteVacancyRequest,
        DestroyModelAction $destroyModelAction
    ): JsonResponse
    {
        $jobBuilder = Vacancy::where('id', $deleteVacancyRequest->data()->vacancy)->where('company_id', $deleteVacancyRequest->data()->user);

        if (!$jobBuilder->exists()) {
            return response()->json([
                'status' => false,
                'title' => 'Vacancy not found',
                'message' => __('Vacancy for found for this company (:company)', [
                    'company' => findUserById($deleteVacancyRequest->data()->user)->name
                ]),
            ], 422);
        }

        $job = $jobBuilder->first();

        $destroyModelAction->execute($job);

        return response()->json([
            'status' => true,
            'message' => __('You have successfully deleted the job (:job)', [
                'job' => $job->title,
            ]),
            'job' => VacancyResources::make($job)
        ]);
    }
}
