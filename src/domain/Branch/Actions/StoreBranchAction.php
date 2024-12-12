<?php

namespace Domain\Branch\Actions;

use Domain\Branch\Data\BranchData;
use Domain\Branch\Models\Branch;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Illuminate\Support\Facades\Auth;
use JsonException;
use Support\Helper\Helper;

class StoreBranchAction
{
    use Helper;

    /**
     * @throws JsonException
     */
    public function execute(
        BranchData $branchData,
        Branch $branch = new Branch()
    ): Branch {

        $isNewBranch = !$branch->exists;

        $branch->forceFill([
            'name' => $branchData->name,
            'description' => $branchData->description,
            'modified_by' => Auth::user()->id,
            'added_by' => $branch->added_by ?? Auth::user()->id,
        ]);

        $branch->save();

        $branch->refresh();

        $user = auth()->user();

        $method = $isNewBranch ? 'created' : 'updated';

        $data = [
            'hasRoute' => true,
            'routeName' => 'Edit Branch',
            'route' => route('admin.branches.show', $branch->id),
        ];

        $notificationAction = new StoreNotificationAction();
        $notificationData = new NotificationData(
            $user->id,
            $user->roles()->first()->name,
            domainStates($method),
            $method === 'created' ? 'Branch Created' : 'Branch Updated',
            $method === 'created' ? 'You\'ve successfully Created Branch' : 'You\'ve successfully Updated Branch',
            data: $data
        );

        $notificationAction->execute($notificationData, user: $user);

        return $branch;
    }
}
