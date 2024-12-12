<?php

namespace Domain\DeviceToken\Actions;

use Domain\DeviceToken\Models\DeviceToken;
use Domain\DeviceToken\Data\DeviceTokenData;

class StoreDeviceTokenAction
{
    public function execute(
        DeviceTokenData $deviceTokenData,
        DeviceToken $deviceToken = new DeviceToken()
    ): void {
        $existingDeviceToken = DeviceToken::query()->where('user_id', $deviceTokenData->userId)
            ->where('device_token', $deviceTokenData->deviceToken)
            ->first();

        if (! $existingDeviceToken) {
            $deviceToken->forceFill([
                'user_id' => $deviceTokenData->userId,
                'device_token' => $deviceTokenData->deviceToken,
                'device_type' => $deviceTokenData->deviceType,
            ]);

            $deviceToken->save();

            $deviceToken->refresh();
        }
    }
}
