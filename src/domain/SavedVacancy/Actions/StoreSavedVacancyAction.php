<?php

namespace Domain\SavedVacancy\Actions;

use Domain\SavedVacancy\Data\SavedVacancyData;
use Domain\SavedVacancy\Models\SavedVacancy;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreSavedVacancyAction
{
    use Helper;

    /**
     * @throws \JsonException
     */
    public function execute(
        SavedVacancyData $savedVacancyData,
        SavedVacancy $savedVacancy = new SavedVacancy()
    ): void {

        $savedVacancy->forceFill([
            'vacancy_id' => $savedVacancyData->vacancyId,
            'candidate_id' => Auth::user()->id,
        ]);

        $savedVacancy->save();

        $savedVacancy->refresh();

        $user = auth()->user();

        $method = 'created' ;

        $data = [
            'hasRoute' => true,
            'routeName' => 'saved-jobs',
            'route' => route('admin.saved-jobs.index'),
        ];

        $notificationAction = new StoreNotificationAction();
        $notificationData = new NotificationData(
            $user->id,
            $user->roles()->first()->name,
            domainStates($method),
            'Saved jobs list',
            'New Vacancy added to the saved jobs list',
            data: $data
        );

        $notificationAction->execute($notificationData, user: $user);
    }
}
