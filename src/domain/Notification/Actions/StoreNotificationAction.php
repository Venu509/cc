<?php

namespace Domain\Notification\Actions;

use Domain\Notification\Models\Notification;
use Domain\Notification\Data\NotificationData;
use Domain\Notification\Services\PushNotification;
use JsonException;

class StoreNotificationAction
{
    /**
     * @throws JsonException
     */
    public function execute(
        NotificationData $notificationData,
        Notification $notification = new Notification(),
        $user = null
    ): void {
        $notification->forceFill([
            'user_id' => $notificationData->user,
            'user_type' => $notificationData->userType,
            'domain' => $notificationData->domain,
            'title' => $notificationData->title,
            'type' => $notificationData->type,
            'data' => json_encode($notificationData->data, JSON_THROW_ON_ERROR | true),
            'message' => $notificationData->message,
            'modified_by' => $user ? $user->id : auth()->user()->id,
            'added_by' => $user ? $user->id : auth()->user()->id,
        ]);

        $notification->save();

        $notification->refresh();

        (new PushNotification())->execute($notification, !is_null($notification->data));
    }
}
