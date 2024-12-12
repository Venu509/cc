<?php

namespace Domain\API\Authentication\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Domain\API\Authentication\Data\ChangePasswordData;

class ChangePasswordAction
{
    public function execute(
        ChangePasswordData $changePasswordData,
        User $user
    ): void {
        $user->fill([
            'password' => Hash::make($changePasswordData->password),
        ]);

        $user->save();

        $user->refresh();
    }
}
