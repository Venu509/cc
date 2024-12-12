<?php

namespace Domain\Notification\Data;

class ReadNotificationData
{
    public function __construct(
        public ?string $id,
        public string $type,
    ) {
    }
}
