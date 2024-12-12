<?php

namespace Domain\Dashboard\ViewModels;

use Domain\Project\Models\Project;
use Domain\Student\Models\Student;
use Domain\User\Enums\ProfileCompletion;
use Domain\Workshop\Models\Workshop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\ViewModels\ViewModel;

class DashboardViewModel extends ViewModel
{
    public function __construct()
    {
    }

    public function data(): array
    {
        $statistics = [];

        if (auth()->user()->hasRole('government') || auth()->user()->hasRole('institution')) {
            $userId = auth()->user()->id;

            $statistics = [
                'students' => $this->calculateMonthlyChange(Student::class, $userId, 'Students', 'orange'),
                'projects' => $this->calculateMonthlyChange(Project::class, $userId, 'Projects', 'yellow'),
                'workshops' => $this->calculateMonthlyChange(Workshop::class, $userId, 'Workshops', 'green'),
            ];
        }

        return [
            'profileCompletion' => auth()->check() ? auth()->user()->getCompletionPercentage() : null,
            'isProfileCompleted' => auth()->check() ? auth()->user()->isStepCompleted(ProfileCompletion::STEP_FIVE) : null,
            'getCompletionStatus' => auth()->check() ? auth()->user()->getCompletionStatus() : null,
            'statistics' => $statistics
        ];
    }

    private function calculateMonthlyChange(
        $model,
        int $userId,
        string $title,
        string $color
    ): array
    {
        $currentMonthCount = $model::where('added_by', $userId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $previousMonthCount = $model::where('added_by', $userId)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();

        $percentageChange = $previousMonthCount > 0
            ? (($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100
            : 0;

        return [
            'title' => __('Total :title', [
                'title' => $title
            ]),
            'data' => [
                $currentMonthCount,
                $previousMonthCount,
                $percentageChange
            ],
            'count' => $model::where('added_by', $userId)->whereMonth('created_at', Carbon::now()->month)->count(),
            'percentageIncrease' => round($percentageChange, 2),
            'type' => $percentageChange > 0 ? 'up' : 'down',
            'color' => $color,
            'message' => __(':percentage% :type from last month', [
                'percentage' => round($percentageChange, 2),
                'type' => $percentageChange > 0 ? 'increase' : 'decrease',
            ]),
        ];
    }
}
