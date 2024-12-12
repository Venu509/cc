<?php

namespace Domain\User\Actions;

use App\Models\User;
use Domain\User\Data\ProfileAvatarDeleteData;
use Support\Helper\Helper;

class RemoveProfileAvatarAction
{
    use Helper;

    public function execute(
        ProfileAvatarDeleteData $profileAvatarDeleteData,
        User $user = new User()
    ): void {
        $user->forceFill([
            'avatar' => null,
            'modified_by' => auth()->user()->id,
        ]);

        $user->save();

        $user->refresh();

        $this->deleteFileIfExists('user-details/'.$user->avatar);
    }
}
