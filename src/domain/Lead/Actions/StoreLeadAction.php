<?php

namespace Domain\Lead\Actions;

use Domain\Lead\Data\LeadData;
use Domain\Lead\Models\Lead;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;

class StoreLeadAction
{
    public function execute(
        LeadData $leadData,
        Lead $lead = new Lead()
    ): Lead {
        $isNewRecord = !$lead->exists;

        $lead->forceFill([
            'title' => $leadData->title,
            'type' => $leadData->type,
            'status' => $leadData->status,
            'description' => $leadData->description,
            'is_active' => true,
            'user_id' => auth()->user()->id,
            'modified_by' => auth()->user()->id,
            'added_by' => auth()->user()->id,
        ]);

        $lead->save();

        $lead->refresh();

        $user = auth()->user();

        $method = $isNewRecord ? 'created' : 'updated';

        $data = [
            'hasRoute' => true,
            'routeName' => 'Edit Lead',
            'route' => route('admin.leads.show', $lead->id),
        ];

        $notificationAction = new StoreNotificationAction();
        $notificationData = new NotificationData(
            $user->id,
            $user->roles()->first()->name,
            domainStates($method),
            $method === 'created' ? 'Lead Created' : 'Lead Updated',
            $method === 'created' ? 'You\'ve successfully Created Lead' : 'You\'ve successfully Updated Lead',
            data: $data
        );

        $notificationAction->execute($notificationData, user: $user);

        return $lead;
    }
}