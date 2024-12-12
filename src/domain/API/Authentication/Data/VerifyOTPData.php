<?php

namespace Domain\API\Authentication\Data;

class VerifyOTPData
{
    public function __construct(
        public ?string $type,
        public string $code,
        public string $via,
        public ?string $email,
        public ?string $phone,
    ) {
    }
}
