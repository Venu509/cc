<?php

namespace Domain\Global\Actions;

use App\Models\User;
use Domain\Global\Notifications\SimilarJobs;
use Domain\Vacancy\Data\VacancyData;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class NotifyCandidateAction
{
    public function execute(
        Vacancy $vacancy,
        VacancyData $vacancyData
    ): void
    {

        User::query()
            ->where('is_enabled_app_notifications', true)
            ->where('is_enabled_push_notifications', true)
            ->whereHas('userDetail', function (Builder $builder) use ($vacancyData) {
                return $builder->where(function($queryBuilder) use ($vacancyData) {
                    return $queryBuilder->where('job_preferences->Role', 'LIKE', "%$vacancyData->title%")
                        ->orWhereIn('job_preferences->EmploymentStatus->value', $vacancyData->workModes)
                        ->orWhereIn('job_preferences->Type->value', $vacancyData->workModes);
                });
            })
            ->whereHas('skills', function (Builder $builder) use ($vacancyData) {
                return $builder->whereIn('title', collect($vacancyData)->pluck('value')->toArray());
            })
            ->whereHas('workExperiences', function (Builder $builder) use ($vacancyData) {
                return $builder->where(function($queryBuilder) use ($vacancyData) {
                    return $queryBuilder
                        ->where('job_title', $vacancyData->title)
                        ->orWhere('job_title', 'LIKE', "%$vacancyData->description%")
                        ->orWhere('responsibilities', 'LIKE', "%$vacancyData->description%")
                        ->orWhere('achievements', 'LIKE', "%$vacancyData->description%");
                });
            })
            ->select(['email', 'phone', 'username', 'login_via'])
            ->get()
            ->each(function ($user) use ($vacancy) {
                if($user->login_via === 'email') {
                    Mail::to($user->email)->send(new SimilarJobs($vacancy));
                }
            });
    }
}