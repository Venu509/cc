<?php

namespace Domain\Notification\Actions;

use Domain\Notification\Models\Notification;

class ReadNotificationAction
{
    public function execute(
        Notification $notification = new Notification()
    ): void {
        $notification->forceFill([
            'is_read' => true,
            'read_at' => now(),
            'modified_by' => auth()->user()->id,
        ]);

        $notification->save();

        $notification->refresh();
    }
}
