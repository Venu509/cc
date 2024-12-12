<?php

namespace Domain\User\Actions;

use App\Models\User;
use Domain\User\Data\UserAssignData;
use Domain\Notification\Data\NotificationData;
use Domain\Notification\Actions\StoreNotificationAction;

class AssignUserLocationAction
{
    public function execute(
        UserAssignData $userAssignData,
        User $user = new User()
    ): void {
        if ($userAssignData->role === 'sales-representative') {
            $city = $userAssignData->topLevelUser['city']['id'];
        } elseif ($userAssignData->city) {
            $city = $userAssignData->city['value'];
        } else {
            $city = null;
        }

        $user->forceFill([
            'region_id' => $userAssignData->region ? $userAssignData->region['value'] : null,
            'area_id' => $userAssignData->area ? $userAssignData->area['value'] : null,
            'city_id' => $city,
            'is_active' => true,
            'is_assigned' => true,
        ]);

        $user->save();

        $user->refresh();

        (new StoreNotificationAction())->execute(
            new NotificationData(
                $user->id,
                $userAssignData->role,
                domainStates('approved'),
                'Profile Approved',
                'Your profile has been approved by admin'
            )
        );
    }
}
