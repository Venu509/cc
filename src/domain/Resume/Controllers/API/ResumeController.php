<?php

namespace Domain\Resume\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Resume\ViewModels\ResumeShowViewModel;
use Domain\Resume\ViewModels\ResumeViewModel;
use Domain\User\Actions\ListCandidatesAction;
use Domain\User\Resources\CandidateResources;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ResumeController extends Controller
{

    public function index():JsonResponse
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 20,
            'search' => request()->has('search') ? request()->get('search') : null,
            'keywords' => request()->has('keywords') ? request()->get('keywords') : null,
            'locations' => request()->has('locations') ? request()->get('locations') : null,
            'qualifications' => request()->has('qualifications') ? request()->get('qualifications') : null,
            'candidateTypes' => request()->has('candidate-types') ? request()->get('candidate-types') : null,
            'industries' => request()->has('industries') ? request()->get('industries') : null,
            'numberOfExperiences' => request()->has('number-of-experiences') ? request()->get('number-of-experiences') : null,
            'noticePeriod' => request()->has('notice-periods') ? request()->get('notice-periods') : null,
            'employmentStatus' => request()->has('employment-status') ? request()->get('employment-status') : null,
            'vacancyId' =>  request()->has('vacancyId') ? request()->get('vacancyId') : null,
            'tab' => request()->has('tab') ? request()->get('tab') : null,
            'roles' => ['candidate'],
        ];

        $params['tab'] = [
            'pending' => ['applied', 'viewed'],
            'shortlisted' => ['shortlisted'],
            'rejected' => ['rejected']
        ][$params['tab']] ?? $params['tab'];

        return response()->json([
            'status' => true,
            'resumes' => (new ListCandidatesAction())->execute($params)
        ]);
    }

    public function show(): JsonResponse
    {
        if(!request()->has('id')) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the user id'
            ], 422);
        }

        $candidateBuilder = User::query()->where('id', (int)request()->get('id'));

        if ($candidateBuilder->doesntExist()) {
            return response()->json([
                'status' => false,
                'message' => 'Requested record not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'candidate' => CandidateResources::make($candidateBuilder->first())
        ]);
    }
}
