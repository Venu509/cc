<?php

namespace Domain\User\Actions;

use App\Models\User;
use Domain\User\Data\ProfileDeleteData;
use Support\Helper\Helper;

class DeleteUserAction
{
    use Helper;

    public function execute(
        ProfileDeleteData $profileDeleteData,
        User $user
    ): void {
        $user->delete();

        $user->save();

        $user->refresh();
    }
}
