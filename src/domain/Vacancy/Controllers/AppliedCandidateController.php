<?php

namespace Domain\Vacancy\Controllers;

use App\Http\Controllers\Controller;
use Domain\Vacancy\Actions\ChangeApplicantStatusAction;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Requests\ChangeApplicantStatusRequest;
use Domain\Vacancy\ViewModels\AppliedCandidateViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use JsonException;
use Support\Helper\Helper;

class AppliedCandidateController extends Controller
{
    use Helper;

    public const INDEX_ROUTE = 'admin.applied-candidates.index';

    public function index(Vacancy $vacancy): InertiaResponse|RedirectResponse
    {
        $viewModel = new AppliedCandidateViewModel(
            20,
            $vacancy
        );

        return Inertia::render('Vacancies/Candidates/Index', $viewModel);
    }

    /**
     * @throws JsonException
     */
    public function status(
        ChangeApplicantStatusRequest $changeApplicantStatusRequest,
        ChangeApplicantStatusAction $changeApplicantStatusAction
    ): RedirectResponse|JsonResponse
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

        if($changeApplicantStatusRequest->data()->isAxiosRequest) {
            return response()->json($responseMsg);
        }

        if (Route::has($changeApplicantStatusRequest->data()->intendedRoute)) {
            return redirect(route('admin.applications.view', [
                'vacancy' => $changeApplicantStatusRequest->data()->vacancy,
                'user' => $changeApplicantStatusRequest->data()->resume,
                'tab' => $changeApplicantStatusRequest->data()->status === 'viewed' ? 'pending' : $changeApplicantStatusRequest->data()->status,
            ]))->withFlash($responseMsg);
        }

        return redirect(route(self::INDEX_ROUTE, [
            'vacancy' => $changeApplicantStatusRequest->data()->vacancy,
            'tab' => $changeApplicantStatusRequest->data()->tab,
        ]))->withFlash($responseMsg);
    }
}
