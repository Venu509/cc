<?php

namespace Domain\API\Authentication\Data;

class LoginData
{
    public function __construct(
        public string $via,
        public ?string $email,
        public ?string $phone,
        public ?string $password,
        public bool $hasPassword,
        public ?string $code,
        public ?string $deviceToken,
        public ?string $deviceType = 'ios',
    ) {
    }
}
