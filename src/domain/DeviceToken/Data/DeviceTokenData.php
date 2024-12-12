<?php

namespace Domain\DeviceToken\Data;

class DeviceTokenData
{
    public function __construct(
        public int $userId,
        public string $deviceToken,
        public ?string $deviceType = 'android',
    ) {
    }
}
