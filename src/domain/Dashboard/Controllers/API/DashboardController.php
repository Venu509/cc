<?php

namespace Domain\Dashboard\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Project\Models\Project;
use Domain\Student\Models\Student;
use Domain\Workshop\Models\Workshop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Support\Helper\Helper;

class DashboardController extends Controller
{
    use Helper;

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched dashboard',
            'data' => $this->data()
        ]);
    }

    private function data(): array
    {
        if (auth()->user()->roles()->first()->name === 'government' || auth()->user()->roles()->first()->name === 'institution') {
            return [
                'totalStudents' => Student::query()->where('added_by', auth()->user()->id)->count(),
                'totalProjects' => Project::query()->where('added_by', auth()->user()->id)->count(),
                'totalWorkshops' => Workshop::query()->where('added_by', auth()->user()->id)->count(),
            ];
        }

        if (auth()->user()->roles()->first()->name === 'candidate') {
            return [
                'profileCompletion' => auth()->user()->getCompletionPercentage(),
            ];
        }

        if (auth()->user()->roles()->first()->name === 'marketing') {
            $userCounts = User::query()
                ->where('added_by', auth()->user()->id)
                ->whereHas('roles', function (Builder $builder) {
                    $builder->whereIn('name', ['institution', 'government', 'candidate', 'company']);
                })
                ->with('roles')
                ->get()
                ->groupBy(function ($user) {
                    return $user->roles->first()->name;
                })
                ->map(fn($group) => $group->count());

            return [
                'totalInstitutes' => $userCounts->get('institution', 0),
                'totalGovernments' => $userCounts->get('government', 0),
                'totalCandidates' => $userCounts->get('candidate', 0),
                'totalCompanies' => $userCounts->get('company', 0),
            ];
        }

        return [];
    }
}
