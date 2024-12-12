<?php

namespace Domain\Application\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Application\ViewModels\ApplicationViewModel;
use Domain\Resume\ViewModels\ResumeShowViewModel;
use Domain\Vacancy\Actions\ChangeApplicantStatusAction;
use Domain\Vacancy\Data\ChangeApplicantStatusData;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\ViewModels\AppliedCandidateViewModel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Support\Helper\Helper;

class ApplicationController extends Controller
{
    use Helper;

    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new ApplicationViewModel(
            20,
        );

        return Inertia::render('Applications/Index', $viewModel);
    }

    public function show(Vacancy $vacancy): InertiaResponse
    {
        $viewModel = new AppliedCandidateViewModel(vacancy: $vacancy);

        return Inertia::render('Applications/Show', $viewModel);
    }

    public function view(Vacancy $vacancy, User $user): InertiaResponse
    {
        $tab = request()->get('tab', 'pending');

        if (!in_array($tab, ['pending', 'shortlisted', 'rejected'])) {
            abort(404);
        }

        $userVacancy = UserVacancy::where('vacancy_id', $vacancy->id)
            ->where('candidate_id', $user->id)->first();

        if ($userVacancy) {
            $trackingHistory = collect($userVacancy->history);

            $historyCount = $trackingHistory->count();

            if ($historyCount === 1 && $userVacancy->status === 'applied') {
                (new ChangeApplicantStatusAction)->execute(
                    new ChangeApplicantStatusData(
                        $vacancy->id,
                        $user->id,
                        true,
                        'viewed',
                        'admin.applications.view'
                    ),
                    $userVacancy
                );
            }
        }

        $viewModel = new ResumeShowViewModel(
            $user,
            $vacancy
        );

        return Inertia::render('Applications/Candidate', $viewModel);
    }
}
