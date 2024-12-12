<?php

namespace Domain\User\Actions;

use App\Models\User;
use Domain\User\Data\ProfileAvatarUploadData;
use Support\Helper\Helper;

class UploadProfileAvatarAction
{
    use Helper;

    public function execute(
        ProfileAvatarUploadData $profileAvatarUploadData,
        User $user = new User()
    ): void {
        $user->forceFill([
            'avatar' => $this->saveFile(
                $user,
                $profileAvatarUploadData->image,
                'avatar',
                'user-details/',
            ),
            'modified_by' => auth()->user()->id,
        ]);

        $user->save();

        $user->refresh();
    }
}
