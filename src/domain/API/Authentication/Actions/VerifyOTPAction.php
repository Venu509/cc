<?php

namespace Domain\API\Authentication\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class VerifyOTPAction
{
    public function execute(
        Builder $userBuilder
    ): Collection
    {
        if ($userBuilder->exists()) {
            $user = $userBuilder->firstOrFail();
            $user->forceFill([
                'code' => null,
                'expired_at' => null,
            ]);
            $user->save();
            $user->refresh();

            return collect([
                'status' => true,
                'user' => $user,
            ]);
        }

        return collect([
            'status' => false,
            'user' => null,
        ]);
    }
}
