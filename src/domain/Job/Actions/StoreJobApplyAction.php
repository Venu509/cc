<?php

namespace Domain\Job\Actions;

use App\Models\User;
use Domain\Job\Enums\ApplicantTracking;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Domain\Vacancy\Data\UserVacancyData;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreJobApplyAction
{
    use Helper;

    public function execute(
        UserVacancyData $userVacancyData,
        UserVacancy $userVacancy = new UserVacancy()
    ): Vacancy {
        $userVacancy->forceFill([
            'vacancy_id' => $userVacancyData->vacancyId,
            'candidate_id' => Auth::user()->id,
            'status' => ApplicantTracking::APPLIED,
        ]);

        $userVacancy->applicantTracking(ApplicantTracking::APPLIED, (auth()->user()->name . ' submitted the resume'));

        $userVacancy->save();

        $userVacancy->refresh();

        $vacancy = Vacancy::where('id', $userVacancyData->vacancyId)->first();

        $method = 'created';

        $data = [
            'hasRoute' => true,
            'routeName' => 'Vacancy',
            'route' => route('admin.vacancies.show', $userVacancyData->vacancyId),
        ];

        $user = findUserById($vacancy->company_id);

        $notificationAction = new StoreNotificationAction();
        $notificationData = new NotificationData(
            $user->id,
            $user->roles()->first()->name,
            domainStates($method),
            'New applicant',
            'You received a new applicant for '.$vacancy->title,
            data: $data
        );

        $notificationAction->execute($notificationData, user: $user);

        return $vacancy;
    }
}
