<?php

namespace Domain\Notification\Data;

class NotificationData
{
    public function __construct(
        public ?string $user,
        public ?string $userType,
        public ?string $domain,
        public ?string $title,
        public ?string $message,
        public ?string $type = 'push',
        public ?array $data = null,
    ) {
    }
}
