<?php

namespace Domain\User\Actions;

use App\Models\User;

class IncrementUserDailyClockInCountAction
{
    public function execute(
        User $user
    ): User {
        $user->forceFill([
            'daily_clock_in_count' => $user->daily_clock_in_count + 1,
        ]);

        $user->save();

        $user->refresh();

        return $user;
    }
}
