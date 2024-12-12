<?php

namespace Domain\User\Actions;

use Domain\User\Data\UserAssignData;
use Domain\Hierarchy\Models\Hierarchy;
use Domain\Notification\Data\NotificationData;
use Domain\Notification\Actions\StoreNotificationAction;

class AssignUserHierarchyAction
{
    public function execute(
        UserAssignData $userAssignData,
        Hierarchy $hierarchy = new Hierarchy()
    ): void {
        $hierarchy->forceFill([
            'parent_id' => $userAssignData->topLevelUser['value'],
            'child_id' => $userAssignData->id,
            'created_at' => auth()->user()->id,
            'updated_at' =>  auth()->user()->id,
        ]);

        $hierarchy->save();

        $hierarchy->refresh();

        (new StoreNotificationAction())->execute(
            new NotificationData(
                $userAssignData->id,
                $userAssignData->role,
                domainStates('assigned'),
                'Profile Assigned',
                'Your profile has been assigned to '.findUserById($userAssignData->topLevelUser['value'])->name
            )
        );
    }
}
