<?php

namespace Domain\Resume\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Resume\ViewModels\ResumeViewModel;
use Domain\Resume\ViewModels\ResumeShowViewModel;
use Domain\User\Actions\ListCandidatesAction;
use Domain\User\Actions\ListUsersAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ResumeController extends Controller
{
    public function index(): InertiaResponse|RedirectResponse
    {
        $viewModel = new ResumeViewModel(
            20,
        );

        return Inertia::render('Resumes/List', $viewModel);
    }

    public function show(User $user): InertiaResponse|RedirectResponse
    {
        $viewModel = new ResumeShowViewModel(
            $user,
        );

        return Inertia::render('Resumes/Show', $viewModel);
    }

    public function fetch(
        ListCandidatesAction $listCandidatesAction
    ): JsonResponse {

        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 20,
            'search' => request()->has('search') ? request()->get('search') : null,
            'keywords' => request()->has('keywords') ? request()->get('keywords') : null,
            'locations' => request()->has('locations') ? request()->get('locations') : null,
            'qualifications' => request()->has('qualifications') ? request()->get('qualifications') : null,
            'jobTypes' => request()->has('job-types') ? request()->get('job-types') : null,
            'industries' => request()->has('industries') ? request()->get('industries') : null,
            'numberOfExperiences' => request()->has('number-of-experiences') ? request()->get('number-of-experiences') : null,
            'noticePeriod' => request()->has('notice-periods') ? request()->get('notice-periods') : null,
            'employmentStatus' => request()->has('employment-status') ? request()->get('employment-status') : null,
            'roles' => ['candidate'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Successfully fetch resumes',
            'resumes' => $listCandidatesAction->execute($params),
        ]);
    }
}
